<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/

namespace Exakat\Tasks;

use Exakat\Analyzer\Analyzer;
use Exakat\Tasks\Helpers\Lock;
use Exakat\Exceptions\NeedsAnalyzerThema;
use Exakat\Exceptions\NoSuchAnalyzer;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\InvalidProjectName;
use Exakat\Exceptions\NoSuchRuleset;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\QueryException;
use Exakat\Exceptions\MissingGremlin;
use Exakat\Exceptions\DSLException;
use ProgressBar\Manager as ProgressBar;
use Exception;
use Exakat\Log;
use Exakat\Config;

class Analyze extends Tasks {
    const CONCURENCE = self::ANYTIME;

    private $progressBar = null;
    private $php = null;
    private $analyzed = array();

    public function setConfig(Config $config): void {
        $this->config = $config;
    }

    public function run(): void {
        if (!$this->config->project->validate()) {
            throw new InvalidProjectName($this->config->project->getError());
        }

        if ($this->config->project->isDefault()) {
            throw new ProjectNeeded();
        }

        if ($this->config->gremlin === 'NoGremlin') {
            throw new MissingGremlin();
        }

        if (!file_exists($this->config->project_dir)) {
            throw new NoSuchProject((string) $this->config->project);
        }

        $this->checkTokenLimit();

        // Take this before we clean it up
        $this->checkAnalyzed();

        if (!empty($this->config->program)) {
            if (is_array($this->config->program)) {
                $analyzersClass = $this->config->program;
            } else {
                $analyzersClass = array($this->config->program);
            }

            foreach($analyzersClass as $analyzer) {
                if (!$this->rulesets->getClass($analyzer)) {
                    throw new NoSuchAnalyzer($analyzer, $this->rulesets);
                }
            }

            $diff = array_uintersect($this->config->ignore_rules, $analyzersClass, 'strcasecmp');
            if (!empty($diff)) {
                display('Ignoring ' . count($diff) . ' rule' . (count($diff) > 1 ? 's' : '') . ' by configuration : ' . implode(', ', $diff));
                $analyzersClass = array_udiff($analyzersClass, $this->config->ignore_rules, 'strcasecmp');
            }

            if (empty($analyzersClass)) {
                throw new NeedsAnalyzerThema();
            }

        } elseif (!empty($this->config->project_rulesets)) {
            $ruleset = $this->config->project_rulesets;

            if ((!$analyzersClass = $this->rulesets->getRulesetsAnalyzers($ruleset)) && ($ruleset[0] !== 'None')) {
                throw new NoSuchRuleset(implode(', ', $ruleset), $this->rulesets->getSuggestionRuleset($ruleset));
            }

            $this->datastore->addRow('hash', array(implode('-', $this->config->project_rulesets) => count($analyzersClass) ) );

            $this->logname = 'analyze.' . strtolower(str_replace(' ', '_', implode('-', $this->config->project_rulesets)));
            $this->log = new Log('analyze.' . strtolower(str_replace(' ', '_', implode('-', $this->config->project_rulesets))),
                                 "{$this->config->projects_root}/projects/{$this->config->project}");
        } else {
            throw new NeedsAnalyzerThema();
        }

        $this->log->log('Analyzing project ' . (string) $this->config->project);
        $this->log->log("Runnable analyzers\t" . count($analyzersClass));

        $this->php = exakat('php');

        $analyzers = array();
        $dependencies = array();
        foreach($analyzersClass as $analyzerClass) {
            $this->fetchAnalyzers($analyzerClass, $analyzers, $dependencies);
        }

        $analyzerList = sort_dependencies($dependencies);
        if (empty($analyzerList)) {
            display("Done\n");
            return;
        }
        if ($this->config->verbose && !$this->config->quiet) {
            $this->progressBar = new Progressbar(0, count($analyzerList) + 1, $this->config->screen_cols);
        }

        foreach($analyzerList as $analyzerClass) {
            if ($this->config->verbose && !$this->config->quiet) {
                echo $this->progressBar->advance();
            }

            assert($analyzers[$analyzerClass] !== null, "Unknown analyzer $analyzerClass from dependsOn()\n");
            $this->analyze($analyzers[$analyzerClass], $analyzerClass);
        }

        if ($this->config->verbose && !$this->config->quiet) {
            echo $this->progressBar->advance();
        }

        display("Done\n");
    }

    private function fetchAnalyzers(string $analyzerClass, array &$analyzers, array &$dependencies): void {
        if (isset($analyzers[$analyzerClass])) {
            return;
        }

        $analyzers[$analyzerClass] = $this->rulesets->getInstance($analyzerClass);

        if ($analyzers[$analyzerClass] === null) {
            display("No such analyzer as $analyzerClass\n");
            return;
        }

        if (isset($this->analyzed[$analyzerClass]) &&
            $this->config->noRefresh === true) {
            display("$analyzerClass is already processed\n");
            return ;
        }

        if ($this->config->noDependencies === true) {
            $dependencies[$analyzerClass] = array();
        } else {
            $dependencies[$analyzerClass] = $analyzers[$analyzerClass]->dependsOn();
            $diff = array_diff($dependencies[$analyzerClass], array_keys($analyzers));
            foreach($diff as $d) {
                if (!isset($analyzers[$d])) {
                    $this->fetchAnalyzers($d, $analyzers, $dependencies);
                }
            }
        }
    }

    private function analyze(Analyzer $analyzer, string $analyzerClass): int {
        $begin = microtime(true);

        $lock = new Lock($this->config->tmp_dir, $analyzerClass);
        if (!$lock->check()) {
            display("Concurency lock activated for $analyzerClass\n");

            return 0;
        }

        if (isset($this->analyzed[$analyzerClass]) && $this->config->noRefresh === true) {
            display( "$analyzerClass is already processed (1)\n");
            return $this->analyzed[$analyzerClass];
        }

        $analyzer->init();

        if (!(!isset($this->analyzed[$analyzerClass]) ||
              $this->config->noRefresh !== true)         ) {
            display("$analyzerClass is already processed (2)\n");

            return $this->analyzed[$analyzerClass];
        }

        $total_results = 0;
        if (!$analyzer->checkPhpVersion($this->config->phpversion)) {
            $analyzerQuoted = $analyzer->getInBaseName();

            $analyzer->storeError('Not Compatible With PHP Version', Analyzer::VERSION_INCOMPATIBLE);

            display("$analyzerQuoted is not compatible with PHP version {$this->config->phpversion}. Ignoring\n");
        } elseif (!$analyzer->checkPhpConfiguration($this->php)) {
            $analyzerQuoted = $analyzer->getInBaseName();

            $analyzer->storeError('Not Compatible With PHP Configuration', Analyzer::CONFIGURATION_INCOMPATIBLE);

            display( "$analyzerQuoted is not compatible with PHP configuration of this version. Ignoring\n");
        } else {
            display( "$analyzerClass running\n");
            try {
                $analyzer->run();
            } catch(DSLException $e) {
                $end = microtime(true);
                display( "$analyzerClass : DSL building exception\n");
                display($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
                $this->log->log("$analyzerClass\t" . ($end - $begin) . "\terror : " . $e->getMessage());
                $this->datastore->addRow('analyzed', array($analyzerClass => 0 ) );
                $this->checkAnalyzed();

            } catch(QueryException $e) {
                $end = microtime(true);
                display("$analyzerClass : Query running exception\n");
                display($e->getMessage());
                $this->log->log("$analyzerClass\t" . ($end - $begin) . "\terror : " . $e->getMessage());
                $counts = $this->gremlin->query('g.V().hasLabel("Analysis").has("analyzer", "' . $analyzer->getInBaseName() . '").property("count", __.out("ANALYZED").count()).values("count")')->toInt();
                $this->datastore->addRow('analyzed', array($analyzerClass => $counts ) );
                $this->checkAnalyzed();

            } catch(Exception $e) {
                $end = microtime(true);
                display( "$analyzerClass : generic exception \n");
                $this->log->log("$analyzerClass\t" . ($end - $begin) . "\texception : " . get_class($e) . "\terror : " . $e->getMessage());
                if (strpos($e->getMessage(), 'The server exceeded one of the timeout settings ') !== false) {
                    $counts = $this->gremlin->query('g.V().hasLabel("Analysis").has("analyzer", "' . $analyzer->getInBaseName() . '").property("count", __.out("ANALYZED").count()).values("count")')->toInt();
                    $this->datastore->addRow('analyzed', array($analyzerClass => $counts ) );
                } else {
                    display($e->getMessage());
                    $this->datastore->addRow('analyzed', array($analyzerClass => 0 ) );
                }
                $this->checkAnalyzed();

                return 0;
            }

            $total_results = $analyzer->getRowCount();
            $processed     = $analyzer->getProcessedCount();
            $queries       = $analyzer->getQueryCount();
            $rawQueries    = $analyzer->getRawQueryCount();

            display( "$analyzerClass run ($total_results / $processed)\n");
            $end = microtime(true);
            $this->log->log("$analyzerClass\t" . ($end - $begin) . "\t$total_results\t$processed\t$queries\t$rawQueries");
            // storing the number of row found in Hash table (datastore)
            $this->datastore->addRow('analyzed', array($analyzerClass => $total_results ) );

            // This also counts the analysis that don't leave data in the database.
            $this->analyzed[$analyzerClass] = $total_results;
        }

        $this->checkAnalyzed();

        return $total_results;
    }

    private function checkAnalyzed(): void {
        $query = <<<'GREMLIN'
g.V().hasLabel("Analysis").as("analyzer", "count").select("analyzer", "count").by("analyzer").by("count");
GREMLIN;
        $res = $this->gremlin->query($query);

        foreach($res as list('analyzer' => $analyzer, 'count' => $count)) {
            if ($count != -1) {
                $this->analyzed[$analyzer] = $count;
            }
        }
    }
}

?>

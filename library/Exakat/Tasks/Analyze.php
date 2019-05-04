<?php
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
use Exakat\Config;
use Exakat\Exceptions\NeedsAnalyzerThema;
use Exakat\Exceptions\NoSuchAnalyzer;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\InvalidProjectName;
use Exakat\Exceptions\NoSuchThema;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\QueryException;
use Exakat\Phpexec;
use Exakat\Project as ProjectName;
use ProgressBar\Manager as ProgressBar;
use Exception;
use Exakat\Log;

class Analyze extends Tasks {
    const CONCURENCE = self::ANYTIME;

    private $progressBar = null;
    private $Php = null;
    private $analyzed = array();

    public function __construct($gremlin, $config, $subtask = Tasks::IS_NOT_SUBTASK) {
        parent::__construct($gremlin, $config, $subtask);
    }

    public function run() {
        $project = new ProjectName($this->config->project);

        if (!$project->validate()) {
            throw new InvalidProjectName($project->getError());
        }

        if ($project == 'default') {
            throw new ProjectNeeded();
        }

        if (!file_exists($this->config->project_dir)) {
            throw new NoSuchProject($project);
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
                if (!$this->themes->getClass($analyzer)) {
                    throw new NoSuchAnalyzer($analyzer, $this->themes);
                }
            }
        } elseif (!empty($this->config->thema)) {
            $thema = $this->config->thema;

            if (!$analyzersClass = $this->themes->getThemeAnalyzers($thema)) {
                throw new NoSuchThema(implode(', ', $thema), $this->themes->getSuggestionThema($thema));
            }

            $this->datastore->addRow('hash', array(implode('-', $this->config->thema) => count($analyzersClass) ) );

            $this->logname = 'analyze.' . strtolower(str_replace(' ', '_', implode('-', $this->config->thema)));
            $this->log = new Log('analyze.' . strtolower(str_replace(' ', '_', implode('-', $this->config->thema))),
                                 "{$this->config->projects_root}/projects/{$this->config->project}");
        } else {
            throw new NeedsAnalyzerThema();
        }

        $this->log->log("Analyzing project $project");
        $this->log->log("Runnable analyzers\t" . count($analyzersClass));

        $this->Php = new Phpexec($this->config->phpversion, $this->config->{'php' . str_replace('.', '', $this->config->phpversion)});

        $analyzers = array();
        $dependencies = array();
        foreach($analyzersClass as $analyzer_class) {
            $this->fetchAnalyzers($analyzer_class, $analyzers, $dependencies);
        }
        
        $analyzerList = sort_dependencies($dependencies);
        if (empty($analyzerList)) {
            display("Done\n");
            return;
        }
        if (!$this->config->verbose && !$this->config->quiet) {
            $this->progressBar = new Progressbar(0, count($analyzerList) + 1, $this->config->screen_cols);
        }
        
        foreach($analyzerList as $analyzer_class) {
            if (!$this->config->verbose && !$this->config->quiet) {
                echo $this->progressBar->advance();
            }

            $this->analyze($analyzers[$analyzer_class], $analyzer_class);
        }

        if (!$this->config->verbose && !$this->config->quiet) {
            echo $this->progressBar->advance();
        }
        
        display("Done\n");
    }
    
    private function fetchAnalyzers($analyzer_class, array &$analyzers, array &$dependencies) {
        if (isset($analyzers[$analyzer_class])) {
            return;
        }

        $analyzers[$analyzer_class] = $this->themes->getInstance($analyzer_class, $this->gremlin, $this->config);
        if ($analyzers[$analyzer_class] === null) {
            display("No such analyzer as $analyzer_class\n");
            return;
        }
     
        if (isset($this->analyzed[$analyzer_class]) &&
             $this->config->noRefresh === true) {
            display( "$analyzer_class is already processed\n");
            return $this->analyzed[$analyzer_class];
        }

        if ($this->config->noDependencies === true) {
            $dependencies[$analyzer_class] = array();
        } else {
            $dependencies[$analyzer_class] = $analyzers[$analyzer_class]->dependsOn();
            $diff = array_diff($dependencies[$analyzer_class], array_keys($analyzers));
            foreach($diff as $d) {
                if (!isset($analyzers[$d])) {
                    $this->fetchAnalyzers($d, $analyzers, $dependencies);
                }
            }
        }
    }

    private function analyze(Analyzer $analyzer, string $analyzer_class) {
        $begin = microtime(true);

        $lock = new Lock($this->config->tmp_dir, $analyzer_class);
        if (!$lock->check()) {
            display(" Concurency lock activated for $analyzer_class \n");
            return false;
        }
        
        if (isset($this->analyzed[$analyzer_class]) &&
             $this->config->noRefresh === true) {
            display( "$analyzer_class is already processed\n");
            return $this->analyzed[$analyzer_class];
        }
        $analyzer->init();

        if (!(!isset($this->analyzed[$analyzer_class]) ||
              $this->config->noRefresh !== true)         ) {
            display( "$analyzer_class is already processed\n");
            
            return $this->analyzed[$analyzer_class];
        }

        if (!$analyzer->checkPhpVersion($this->config->phpversion)) {
            $analyzerQuoted = str_replace('\\', '\\\\', get_class($analyzer));

            $analyzer = str_replace('\\', '\\\\', $analyzer_class);

            $query = <<<GREMLIN
result = g.addV('Noresult').property('code',                        'Not Compatible With PhpVersion')
                       .property('fullcode',                    'Not Compatible With PhpVersion')
                       .property('virtual',                      true)
                       .property('atom',                         'Noresult')
                       .property('notCompatibleWithPhpVersion', '{$this->config->phpversion}')
                       .property('token',                       'T_INCOMPATIBLE');

g.addV('Analysis').property('analyzer', '$analyzerQuoted').property("Atom", "Analysis").addE('ANALYZED').to(result);

GREMLIN;
            $this->gremlin->query($query);
            $this->datastore->addRow('analyzed', array($analyzer_class => -2 ) );

            display("$analyzer is not compatible with PHP version {$this->config->phpversion}. Ignoring\n");
            $total_results = 0;
        } elseif (!$analyzer->checkPhpConfiguration($this->Php)) {
            $analyzerQuoted = str_replace('\\', '\\\\', get_class($analyzer));
            $analyzer = str_replace('\\', '\\\\', $analyzer_class);

            $query = <<<GREMLIN
result = g.addV('Noresult').property('code',                              'Not Compatible With Configuration')
                       .property('fullcode',                          'Not Compatible With Configuration')
                       .property('virtual',                            true)
                       .property('atom',                         'Noresult')
                       .property('notCompatibleWithPhpConfiguration', '{$this->config->phpversion}')
                       .property('token',                             'T_INCOMPATIBLE');

index = g.addV('Analysis').property('analyzer', '$analyzerQuoted').property("Atom", "Analysis").addE('ANALYZED').to(result);
GREMLIN;
            $this->gremlin->query($query);
            $this->datastore->addRow('analyzed', array($analyzer_class => -1 ) );

            display( "$analyzer is not compatible with PHP configuration of this version. Ignoring\n");
            $total_results = 0;
        } else {
            display( "$analyzer_class running\n");
            try {
                $analyzer->run($this->config);
            } catch(QueryException $e) {
                $end = microtime(true);
                display( "$analyzer_class : DSL building exception\n");
                display($e->getMessage());
                $this->log->log("$analyzer_class\t" . ($end - $begin) . "\terror : " . $e->getMessage());
                $this->datastore->addRow('analyzed', array($analyzer_class => 0 ) );
                $this->checkAnalyzed();

            } catch(Exception $e) {
                $end = microtime(true);
                display( "$analyzer_class : error \n");
                display($e->getMessage());
                $this->log->log("$analyzer_class\t" . ($end - $begin) . "\terror : " . $e->getMessage());
                $this->datastore->addRow('analyzed', array($analyzer_class => 0 ) );
                $this->checkAnalyzed();

                return 0;
            }

            $count      = $analyzer->getRowCount();
            $processed  = $analyzer->getProcessedCount();
            $queries    = $analyzer->getQueryCount();
            $rawQueries = $analyzer->getRawQueryCount();
            $total_results = $count;
            display( "$analyzer_class run ($count / $processed)\n");
            $end = microtime(true);
            $this->log->log("$analyzer_class\t" . ($end - $begin) . "\t$count\t$processed\t$queries\t$rawQueries");
            // storing the number of row found in Hash table (datastore)
            $this->datastore->addRow('analyzed', array($analyzer_class => $count ) );
        }

        $this->checkAnalyzed();

        return $total_results;
    }
    
    private function checkAnalyzed() {
        $rows = $this->datastore->getRow('analyzed');
        foreach($rows as $row) {
            if (!isset($this->analyzed[$row['analyzer']])) {
                $this->analyzed[$row['analyzer']] = $row['counts'];
            }
        }
    }
}

?>

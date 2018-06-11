<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Exceptions\DependsOnMustReturnArray;
use Exakat\Exceptions\NeedsAnalyzerThema;
use Exakat\Exceptions\NoSuchAnalyzer;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\NoSuchThema;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Phpexec;
use ProgressBar\Manager as ProgressBar;
use Exception;

class Analyze extends Tasks {
    const CONCURENCE = self::ANYTIME;
    
    private $progressBar = null;
    private $Php = null;
    private $analyzed = array();

    public function __construct($gremlin, $config, $subtask = Tasks::IS_NOT_SUBTASK) {
        if (!empty($config->thema)) {
            $this->logname = 'analyze.'.strtolower(str_replace(' ', '_', $config->thema));
        }

        parent::__construct($gremlin, $config, $subtask);
    }

    public function run() {
        $project = $this->config->project;

        if ($project == 'default') {
            throw new ProjectNeeded($project);
        }

        if (!file_exists($this->config->projects_root.'/projects/'.$project)) {
            throw new NoSuchProject($project);
        }

        $this->checkTokenLimit();
        $begin = microtime(true);

        // Take this before we clean it up
        $this->checkAnalyzed();
        
        if ($this->config->program !== null) {
            if (is_array($this->config->program)) {
                $analyzers_class = $this->config->program;
            } else {
                $analyzers_class = array($this->config->program);
            }

            foreach($analyzers_class as $analyzer) {
                if (!$this->themes->getClass($analyzer)) {
                    throw new NoSuchAnalyzer($analyzer, $this->themes);
                }
            }
        } elseif (is_string($this->config->thema)) {
            $thema = $this->config->thema;

            if (!$analyzers_class = $this->themes->getThemeAnalyzers($thema)) {
                throw new NoSuchAnalyzer($thema, $this->themes);
            }

            $this->datastore->addRow('hash', array($this->config->thema => count($analyzers_class) ) );
        } else {
            throw new NeedsAnalyzerThema();
        }

        $this->log->log("Analyzing project $project");
        $this->log->log("Runnable analyzers\t".count($analyzers_class));

        $total_results = 0;
        $this->Php = new Phpexec($this->config->phpversion, $this->config->{'php' . str_replace('.', '', $this->config->phpversion)});

        if (!$this->config->verbose && !$this->config->quiet) {
           $this->progressBar = new Progressbar(0, count($analyzers_class) + 1, exec('tput cols'));
        }

        foreach($analyzers_class as $analyzer_class) {
            if (!$this->config->verbose && !$this->config->quiet) {
                echo $this->progressBar->advance();
            }

            $this->analyze($analyzer_class);
        }

        if (!$this->config->verbose && !$this->config->quiet) {
            echo $this->progressBar->advance();
        }
        
        display( "Done\n");
    }

    private function analyze($analyzer_class) {
        $begin = microtime(true);

        $analyzer = $this->themes->getInstance($analyzer_class, $this->gremlin, $this->config);
        if ($analyzer === null) {
            display("No such analyzer as $analyzer_class\n");
            return false;
        }

        $lock = new Lock($this->config->projects_root.'/projects/.exakat/', $analyzer_class);
        if (!$lock->check()) {
            display(" Concurency lock activated for $analyzer_class \n");
            return false;
        }
        
        if (!(!isset($this->analyzed[$analyzer_class]) ||
              $this->config->noRefresh !== true)
            ) {
            display( "$analyzer_class is already processed\n");
            
            return $this->analyzed[$analyzer_class];
        }
        $analyzer->init();
        
        if ($this->config->noDependencies !== true) {
            foreach($analyzer->dependsOn() as $dependency) {
                if (!isset($this->analyzed[$dependency]) ||
                     ($this->config->noRefresh  === false)   ) {
                    $count = $this->analyze($dependency);
                    assert($count !== null, "count is null");
                    $this->analyzed[$dependency] = $count;
                    $this->checkAnalyzed();
                }
            }
        }

        if (!(!isset($this->analyzed[$analyzer_class]) ||
              $this->config->noRefresh !== true)
            ) {
            display( "$analyzer_class is already processed 2\n");
            
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
            } catch(Exception $error) {
                $end = microtime(true);
                display( "$analyzer_class : error \n");
                display($error->getMessage());
                $this->log->log("$analyzer_class\t".($end - $begin)."\terror : ".$error->getMessage());
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
            $this->log->log("$analyzer_class\t".($end - $begin)."\t$count\t$processed\t$queries\t$rawQueries");
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

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

use Exception;
use Exakat\Analyzer\Analyzer;
use Exakat\Config;
use Exakat\Datastore;
use Exakat\Exceptions\NoSuchFile;
use Exakat\Exceptions\NoSuchDir;
use Exakat\Exceptions\NoSuchAnalyzer;
use Exakat\Tasks\CleanDb;
use Exakat\Tasks\Clean;

class Codacy extends Tasks {
    const CONCURENCE = self::NONE;

    public function run() {

        // Database is not cleaned, as the docker container is supposed to be started and clean.
        if (isset($this->config->codacy_error)) {
            $error = new \Stdclass();
            $error->filename = ".codacy.json";
            $error->message  = $this->config->codacy_error;

            return;
        }
        
        if ($this->config->codacy_files === 'all') {
            display("Running files".PHP_EOL);
            $analyze = new Files($this->gremlin, $this->config, Tasks::IS_SUBTASK);
            $analyze->run();
            unset($analyze);
        } else {
            $this->datastore->cleanTable('files');
            $this->datastore->cleanTable('analyzed');
            $this->datastore->addRow('files',
                                      array_map(function ($a) { return array('file'   => "/$a");}, $this->config->codacy_files));
        }
        
        $load = new Load($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $load->run();
        unset($load);
        display("Project loaded\n");
        
        $args = array ( 1 => 'analyze',
                        2 => '-p',
                        3 => $this->config->project,
                        4 => '-norefresh',
                        5 => '-u',
                        );
        
        if ($this->config->quiet) {
            $args[] = '-q';
        }

        if ($this->config->codacy_analyzers === 'all') {
            $args[] = '-T';
            $args[] = 'Codacy';

            try {
                $analyzeConfig = new Config($args);
    
                $analyze = new Analyze($this->gremlin, $analyzeConfig, Tasks::IS_SUBTASK);
                $analyze->run();
            } catch (Exception $e) {
                
            }
            unset($analyze);

            $args = array ( 1 => 'dump',
                            2 => '-p',
                            3 => $this->config->project,
                            4 => '-T',
                            5 => 'Codacy',
                        );
            $dumpConfig = new Config($args);
            $dump = new Dump($this->gremlin, $dumpConfig, Tasks::IS_SUBTASK);
            $dump->run();
            unset($dump);
        } else {
            foreach($this->config->codacy_analyzers as $analyzer) {
                $args[] = '-P';
                $args[] = $analyzer;

                try {
                    $analyzeConfig = new Config($args);
        
                    $analyze = new Analyze($this->gremlin, $analyzeConfig, Tasks::IS_SUBTASK);
                    $analyze->run();
                } catch (Exception $e) {
                    
                }
                unset($analyze);
    
                $args = array ( 1 => 'dump',
                                2 => '-p',
                                3 => $this->config->project,
                                4 => '-P',
                                5 => $analyzer,
                                6 => '-u',
                            );
                $dumpConfig = new Config($args);
                $dump = new Dump($this->gremlin, $dumpConfig, Tasks::IS_SUBTASK);
                $dump->run();
                unset($dump);
            }
        }

        $report = new Report($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $report->run();
        unset($report);

        display("Analyzed project\n");
    }
}

?>
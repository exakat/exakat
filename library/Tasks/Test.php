<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Tasks;

class Test extends Tasks {
    private $project_dir = '.';
    private $config = null;
    
    public function run(\Config $config) {
        $this->config = $config;
        $project = 'test';

        // Check for requested file
        if (!empty($config->filename) && !file_exists($config->filename)) {
            die("No such file '$config->filename'. Aborting\n");
        } elseif (!empty($config->dirname) && !file_exists($config->dirname)) {
            die("No such directory '$config->filename'. Aborting\n");
        }

        // Check for requested analyze
        $analyzer = $config->program;
        if (\Analyzer\Analyzer::getClass($analyzer)) {
            $analyzers_class = array($analyzer);
        } else {
            $r = \Analyzer\Analyzer::getSuggestionClass($analyzer);
            if (count($r) > 0) {
                echo 'did you mean : ', implode(', ', str_replace('_', '/', $r)), "\n";
            }
            die("No such class as '$analyzer'. Aborting\n");
        }

        display("Cleaning DB\n");
        shell_exec($this->config->php.' '.$config->executable.' cleandb -v');

        if (!empty($config->dirname)) {
            shell_exec($this->config->php.' '.$config->executable.' load -v -p test -r -d '.$config->dirname. ' > '.$config->projects_root.'/projects/test/log/load.final.log' );
        } else {
            shell_exec($this->config->php.' '.$config->executable.' load -v -p test -f '.$config->filename. ' > '.$config->projects_root.'/projects/test/log/load.final.log' );
        }
        display("Project loaded\n");

        $res = shell_exec($this->config->php.' '.$config->executable.' build_root -v -p test > '.$config->projects_root.'/projects/test/log/build_root.final.log');
        display("Build root\n");

        $res = shell_exec($this->config->php.' '.$config->executable.' tokenizer -p test > '.$config->projects_root.'/projects/test/log/tokenizer.final.log');
        display("Project tokenized\n");

        $args = array ( 1 => 'analyze',
                        2 => '-p',
                        3 => 'test',
                        4 => '-P',
                        5 => $config->program,
                        6 => '-q'
                        );
        
        try {
            $configThema = \Config::push($args);

            $analyze = new Analyze();
            $analyze->run($configThema);
            unset($report);
            
            \Config::pop();
        } catch (\Exception $e) {
            echo "Error while running the Analyze $theme \n",
                 $e->getMessage();
            die();
        }

        display("Analyzed project\n");
    }
}

?>

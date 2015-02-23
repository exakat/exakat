<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class Exakat {
    const VERSION = '0.1.0';
    const BUILD = '73';
    
    public function execute(Config $config) {
        switch ($config->command) {
            case 'doctor' : 
                $doctor = new Tasks\Doctor();
                print $doctor->run($config);
                break;

            case 'init' : 
                $task = new Tasks\Initproject();
                print $task->run($config);
                break;

            case 'files' : 
                $task = new Tasks\Files();
                print $task->run($config);
                break;

            case 'build_root' : 
                $task = new Tasks\Build_root();
                print $task->run($config);
                break;

            case 'load' : 
                $task = new Tasks\Load();
                print $task->run($config);
                break;

            case 'stat' : 
                $task = new Tasks\Stat();
                print $task->run($config);
                break;

            case 'tokenizer' : 
                $task = new Tasks\Tokenizer();
                print $task->run($config);
                break;

            case 'analyze' : 
                $task = new Tasks\Analyze();
                print $task->run($config);
                break;

            case 'results' : 
                $task = new Tasks\Results();
                print $task->run($config);
                break;

            case 'export' : 
                $task = new Tasks\Export();
                print $task->run($config);
                break;

            case 'errors' : 
                $task = new Tasks\Errors();
                print $task->run($config);
                break;

            case 'report' : 
                $task = new Tasks\Report();
                print $task->run($config);
                break;

            case 'report_all' : 
                $task = new Tasks\ReportAll();
                print $task->run($config);
                break;

            case 'project' : 
                $task = new Tasks\Project();
                print $task->run($config);
                break;

            case 'log2csv' : 
                $task = new Tasks\Log2csv();
                print $task->run($config);
                break;

            case 'magicnumber' : 
                $task = new Tasks\Magicnumber();
                print $task->run($config);
                break;

            case 'version' : 
            default : 
                $version = self::VERSION;
                $date = date('r', filemtime(__FILE__));
                print "Exakat : @ 2014-2015 Damien Seguy. 
Version : {$version} - $date\n";
                break;
        }
    }
}

?>

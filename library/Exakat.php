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

class Exakat {
    const VERSION = '0.5.0';
    const BUILD = '291';
    
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
                $task = new Tasks\Report2();
                print $task->run($config);
                break;

            case 'report2' : 
                $task = new Tasks\Report2();
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

            case 'projectspip' : 
                $task = new Tasks\ProjectSpip();
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

            case 'clean' : 
                $task = new Tasks\Clean();
                print $task->run($config);
                break;

            case 'status' : 
                $task = new Tasks\Status();
                print $task->run($config);
                break;

            case 'help' : 
                $task = new Tasks\Help();
                print $task->run($config);
                break;

            case 'constantes' : 
                $task = new Tasks\ConstantStructures();
                print $task->run($config);
                break;

            case 'cleandb' : 
                $task = new Tasks\CleanDb();
                print $task->run($config);
                break;

            case 'onepage' : 
                $task = new Tasks\OnePage();
                print $task->run($config);
                break;

            case 'update' : 
                $task = new Tasks\Update();
                print $task->run($config);
                break;

            case 'onepagereport' : 
                $task = new Tasks\OnepageReport();
                print $task->run($config);
                break;

            case 'phploc' : 
                $task = new Tasks\Phploc();
                print $task->run($config);
                break;

            case 'findextlib' : 
                $task = new Tasks\FindExternalLibraries();
                print $task->run($config);
                break;

            case 'dump' : 
                $task = new Tasks\Dump();
                print $task->run($config);
                break;

            case 'jobqueue' : 
                $task = new Tasks\Jobqueue();
                print $task->run($config);
                break;

            case 'queue' : 
                $task = new Tasks\Queue();
                print $task->run($config);
                break;

            case 'vector' : 
                $task = new Tasks\Vector();
                print $task->run($config);
                break;

            case 'classes' : 
                $task = new Tasks\Classes();
                print $task->run($config);
                break;

            case 'test' : 
                $task = new Tasks\Test();
                print $task->run($config);
                break;

            case 'remove' : 
                $task = new Tasks\Remove();
                print $task->run($config);
                break;

            case 'server' : 
                $task = new Tasks\Server();
                print $task->run($config);
                break;

            case 'version' : 
            default : 
                $version = self::VERSION;
                $build = self::BUILD;
                $date = date('r', filemtime(__FILE__));
                echo "
 ________                 __              _    
|_   __  |               [  |  _         / |_  
  | |_ \_| _   __  ,--.   | | / ]  ,--. `| |-' 
  |  _| _ [ \ [  ]`'_\ :  | '' <  `'_\ : | |   
 _| |__/ | > '  < // | |, | |`\ \ // | |,| |,  
|________|[__]`\_]\'-;__/[__|  \_]\'-;__/\__/  
                                               

Exakat : @ 2014-2016 Damien Seguy. 
Version : ", $version, ' - Build ', $build, ' - ', $date, "\n";

                break;
        }
    }
}

?>

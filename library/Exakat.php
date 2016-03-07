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
    const VERSION = '0.5.5';
    const BUILD = 314;
    
    public function execute(Config $config) {
        switch ($config->command) {
            case 'doctor' : 
                $doctor = new Tasks\Doctor();
                $doctor->run($config);
                break;

            case 'init' : 
                $task = new Tasks\Initproject();
                $task->run($config);
                break;

            case 'files' : 
                $task = new Tasks\Files();
                $task->run($config);
                break;

            case 'build_root' : 
                $task = new Tasks\Build_root();
                $task->run($config);
                break;

            case 'load' : 
                $task = new Tasks\Load();
                $task->run($config);
                break;

            case 'stat' : 
                $task = new Tasks\Stat();
                $task->run($config);
                break;

            case 'tokenizer' : 
                $task = new Tasks\Tokenizer();
                $task->run($config);
                break;

            case 'analyze' : 
                $task = new Tasks\Analyze();
                $task->run($config);
                break;

            case 'results' : 
                $task = new Tasks\Results();
                $task->run($config);
                break;

            case 'export' : 
                $task = new Tasks\Export();
                $task->run($config);
                break;

            case 'errors' : 
                $task = new Tasks\Errors();
                $task->run($config);
                break;

            case 'report' : 
                $task = new Tasks\Report2();
                $task->run($config);
                break;

            case 'report2' : 
                $task = new Tasks\Report2();
                $task->run($config);
                break;

            case 'report_all' : 
                $task = new Tasks\ReportAll();
                $task->run($config);
                break;

            case 'project' : 
                $task = new Tasks\Project();
                $task->run($config);
                break;

            case 'projectspip' : 
                $task = new Tasks\ProjectSpip();
                $task->run($config);
                break;

            case 'log2csv' : 
                $task = new Tasks\Log2csv();
                $task->run($config);
                break;

            case 'magicnumber' : 
                $task = new Tasks\Magicnumber();
                $task->run($config);
                break;

            case 'clean' : 
                $task = new Tasks\Clean();
                $task->run($config);
                break;

            case 'status' : 
                $task = new Tasks\Status();
                $task->run($config);
                break;

            case 'help' : 
                $task = new Tasks\Help();
                $task->run($config);
                break;

            case 'constantes' : 
                $task = new Tasks\ConstantStructures();
                $task->run($config);
                break;

            case 'cleandb' : 
                $task = new Tasks\CleanDb();
                $task->run($config);
                break;

            case 'onepage' : 
                $task = new Tasks\OnePage();
                $task->run($config);
                break;

            case 'update' : 
                $task = new Tasks\Update();
                $task->run($config);
                break;

            case 'onepagereport' : 
                $task = new Tasks\OnepageReport();
                $task->run($config);
                break;

            case 'phploc' : 
                $task = new Tasks\Phploc();
                $task->run($config);
                break;

            case 'findextlib' : 
                $task = new Tasks\FindExternalLibraries();
                $task->run($config);
                break;

            case 'dump' : 
                $task = new Tasks\Dump();
                $task->run($config);
                break;

            case 'jobqueue' : 
                $task = new Tasks\Jobqueue();
                $task->run($config);
                break;

            case 'queue' : 
                $task = new Tasks\Queue();
                $task->run($config);
                break;

            case 'vector' : 
                $task = new Tasks\Vector();
                $task->run($config);
                break;

            case 'classes' : 
                $task = new Tasks\Classes();
                $task->run($config);
                break;

            case 'test' : 
                $task = new Tasks\Test();
                $task->run($config);
                break;

            case 'remove' : 
                $task = new Tasks\Remove();
                $task->run($config);
                break;

            case 'server' : 
                $task = new Tasks\Server();
                $task->run($config);
                break;

            case 'upgrade' : 
                $task = new Tasks\Upgrade();
                $task->run($config);
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

<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Exakat;

use Exakat\Tasks;
use Exakat\Config;

class Exakat {
    const VERSION = '1.1.2';
    const BUILD = 688;

    private $gremlin = null;
    private $config = null;

    public function __construct($gremlin, $config) {
        $this->gremlin = $gremlin;
        $this->config  = $config;
    }

    public function execute(Config $config) {
        switch ($config->command) {
            case 'doctor' :
                $doctor = new Tasks\Doctor($this->gremlin, $this->config);
                $doctor->run();
                break;

            case 'init' :
                $task = new Tasks\Initproject($this->gremlin, $this->config);
                $task->run();
                break;

            case 'anonymize' :
                $task = new Tasks\Anonymize($this->gremlin, $this->config);
                $task->run();
                break;

            case 'files' :
                $task = new Tasks\Files($this->gremlin, $this->config);
                $task->run();
                break;

            case 'load' :
                $task = new Tasks\Load($this->gremlin, $this->config);
                $task->run();
                break;

            case 'stat' :
                $task = new Tasks\Stat($this->gremlin, $this->config);
                $task->run();
                break;

            case 'catalog' :
                $task = new Tasks\Catalog($this->gremlin, $this->config);
                $task->run();
                break;

            case 'analyze' :
                $task = new Tasks\Analyze($this->gremlin, $this->config);
                $task->run();
                break;

            case 'results' :
                $task = new Tasks\Results($this->gremlin, $this->config);
                $task->run();
                break;

            case 'export' :
                $task = new Tasks\Export($this->gremlin, $this->config);
                $task->run();
                break;

            case 'report' :
                $task = new Tasks\Report2($this->gremlin, $this->config);
                $task->run();
                break;

            case 'project' :
                $task = new Tasks\Project($this->gremlin, $this->config);
                $task->run();
                break;

            case 'melis' :
                $task = new Tasks\Melis($this->gremlin, $this->config);
                $task->run();
                break;

            case 'clean' :
                $task = new Tasks\Clean($this->gremlin, $this->config);
                $task->run();
                break;

            case 'status' :
                $task = new Tasks\Status($this->gremlin, $this->config);
                $task->run();
                break;

            case 'help' :
                $task = new Tasks\Help($this->gremlin, $this->config);
                $task->run();
                break;

            case 'cleandb' :
                $task = new Tasks\CleanDb($this->gremlin, $this->config);
                $task->run();
                break;

            case 'onepage' :
                $task = new Tasks\OnePage($this->gremlin, $this->config);
                $task->run();
                break;

            case 'update' :
                $task = new Tasks\Update($this->gremlin, $this->config);
                $task->run();
                break;

            case 'findextlib' :
                $task = new Tasks\FindExternalLibraries($this->gremlin, $this->config);
                $task->run();
                break;

            case 'dump' :
                $task = new Tasks\Dump($this->gremlin, $this->config);
                $task->run();
                break;

            case 'jobqueue' :
                $task = new Tasks\Jobqueue($this->gremlin, $this->config);
                $task->run();
                break;

            case 'queue' :
                $task = new Tasks\Queue($this->gremlin, $this->config);
                $task->run();
                break;

            case 'test' :
                $task = new Tasks\Test($this->gremlin, $this->config);
                $task->run();
                break;

            case 'remove' :
                $task = new Tasks\Remove($this->gremlin, $this->config);
                $task->run();
                break;

            case 'server' :
                $task = new Tasks\Server($this->gremlin, $this->config);
                $task->run();
                break;

            case 'upgrade' :
                $task = new Tasks\Upgrade($this->gremlin, $this->config);
                $task->run();
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
                                               

Exakat : @ 2014-2017 Damien Seguy. 
Version : ", $version, ' - Build ', $build, ' - ', $date, "\n";

                break;
        }
    }
}

?>

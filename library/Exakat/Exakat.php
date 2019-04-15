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

namespace Exakat;

use Exakat\Tasks;
use Exakat\Config;

class Exakat {
    const VERSION = '1.7.4';
    const BUILD = 908;

    private $gremlin = null;
    private $config = null;

    public function __construct($gremlin, $config) {
        $this->gremlin = $gremlin;
        $this->config  = $config;
    }

    public function execute(Config $config) {
        if ($config->remote === 'none') {
            $this->local($config);
        } else {
            $this->remote($config);
        }
    }
    
    private function remote(Config $config) {
        $json = $config->commandLineJson();

        $remote = new Remote($config->remotes[$config->remote], $this->config->transit_key);
        
        switch ($config->command) {
            case 'init' :
                // replicate init, because we'll need later
                $task = new Tasks\Initproject($this->gremlin, $this->config);
                $task->run();

                // Local load before remote, in case both are identical.
                $res = $remote->send($json);
                break;

            case 'fetch' :
                $res = $remote->send($json);

                if (strlen($res) < 1024) {
                    // This is an error
                    $json = json_decode($res);
                    if (empty($json)) {
                        print "Couldn't read an answer from remote.\n";
                        return;
                    }
                    
                    if (empty($json->error)) {
                        print "Couldn't read an error from remote.\n";
                        return;
                    }
                    
                    print "Error: $json->error\n";
                    return;
                }

                file_put_contents("{$config->projects_root}/projects/{$config->project}/dump.zip", $res);
                if (file_exists("{$config->projects_root}/projects/{$config->project}/dump.sqlite")) {
                    unlink("{$config->projects_root}/projects/{$config->project}/dump.sqlite");
                }
                shell_exec("cd {$config->projects_root}/projects/{$config->project}; unzip dump.zip && rm dump.zip");
                display("Fetched\n");

                break;

            case 'status' :
                $res = $remote->send($json);
                print $res;
                break;

            default :
                $res = $remote->send($json);
                print $res;
                break;
        }
    }
        
    private function local(Config $config) {
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
                $task = new Tasks\Report($this->gremlin, $this->config);
                $task->run();
                break;

            case 'project' :
                $task = new Tasks\Project($this->gremlin, $this->config);
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

            case 'api' :
                $task = new Tasks\Api($this->gremlin, $this->config);
                $task->run();
                break;

            case 'upgrade' :
                $task = new Tasks\Upgrade($this->gremlin, $this->config);
                $task->run();
                break;

            case 'fetch' :
                $task = new Tasks\Fetch($this->gremlin, $this->config);
                $task->run();
                break;

            case 'proxy' :
                $task = new Tasks\Proxy($this->gremlin, $this->config);
                $task->run();
                break;

            case 'extension' :
                $task = new Tasks\Extension($this->gremlin, $this->config);
                $task->run();
                break;

            case 'config' :
                $task = new Tasks\Config($this->gremlin, $this->config);
                $task->run();
                break;

            case 'show' :
                $task = new Tasks\Show($this->gremlin, $this->config);
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
                                               

Exakat : @ 2014-2019 Damien Seguy. 
Version : ", $version, ' - Build ', $build, ' - ', $date, "\n";

                break;
        }
    }
}

?>

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
use Exakat\Graph\Graph;
use Exakat\Config;
use Exakat\Configsource\Commandline;

class Exakat {
    const VERSION = '2.0.6';
    const BUILD = 1027;
    
    private $config  = null;
    private $gremlin = null;
    
    public function __construct() {
        $this->config = exakat('config');
        $this->gremlin = exakat('graphdb');
    }

    public function execute() : void {
        if ($this->config->remote === 'none') {
            $this->local($this->config);
        } else {
            $this->remote($this->config);
        }
    }
    
    private function remote() : void {
        $json = $this->config->commandLineJson();

        $remote = new Remote($this->config->remotes[$this->config->remote], $this->config->transit_key);
        
        switch ($this->config->command) {
            case 'init' :
                // replicate init, because we'll need later
                $task = new Tasks\Initproject();
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

                file_put_contents("{$this->config->projects_root}/projects/{$this->config->project}/dump.zip", $res);
                if (file_exists("{$this->config->projects_root}/projects/{$this->config->project}/dump.sqlite")) {
                    unlink("{$this->config->projects_root}/projects/{$this->config->project}/dump.sqlite");
                }
                shell_exec("cd {$this->config->projects_root}/projects/{$this->config->project}; unzip dump.zip && rm dump.zip");
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
        
    private function local() : void {
        switch ($this->config->command) {
            case 'doctor' :
                $doctor = new Tasks\Doctor();
                $doctor->run();
                break;

            case 'init' :
                $task = new Tasks\Initproject();
                $task->run();
                break;

            case 'anonymize' :
                $task = new Tasks\Anonymize();
                $task->run();
                break;

            case 'files' :
                $task = new Tasks\Files();
                $task->run();
                break;

            case 'load' :
                $task = new Tasks\Load();
                $task->run();
                break;

            case 'diff' :
                $task = new Tasks\Diff();
                $task->run();
                break;

            case 'stat' :
                $task = new Tasks\Stat();
                $task->run();
                break;

            case 'catalog' :
                $task = new Tasks\Catalog();
                $task->run();
                break;

            case 'analyze' :
                $task = new Tasks\Analyze();
                $task->run();
                break;

            case 'results' :
                $task = new Tasks\Results();
                $task->run();
                break;

            case 'export' :
                $task = new Tasks\Export();
                $task->run();
                break;

            case 'report' :
                $task = new Tasks\Report();
                $task->run();
                break;

            case 'project' :
                $task = new Tasks\Project();
                $task->run();
                break;

            case 'clean' :
                $task = new Tasks\Clean();
                $task->run();
                break;

            case 'status' :
                $task = new Tasks\Status();
                $task->run();
                break;

            case 'help' :
                $task = new Tasks\Help();
                $task->run();
                break;

            case 'cleandb' :
                $task = new Tasks\CleanDb();
                $task->run();
                break;

            case 'onepage' :
                $task = new Tasks\OnePage();
                $task->run();
                break;

            case 'update' :
                $task = new Tasks\Update();
                $task->run();
                break;

            case 'findextlib' :
                $task = new Tasks\FindExternalLibraries();
                $task->run();
                break;

            case 'dump' :
                $task = new Tasks\Dump();
                $task->run();
                break;

            case 'jobqueue' :
                $task = new Tasks\Jobqueue();
                $task->run();
                break;

            case 'queue' :
                $task = new Tasks\Queue();
                $task->run();
                break;

            case 'test' :
                $task = new Tasks\Test();
                $task->run();
                break;

            case 'remove' :
                $task = new Tasks\Remove();
                $task->run();
                break;

            case 'server' :
                $task = new Tasks\Server();
                $task->run();
                break;

            case 'api' :
                $task = new Tasks\Api();
                $task->run();
                break;

            case 'upgrade' :
                $task = new Tasks\Upgrade();
                $task->run();
                break;

            case 'fetch' :
                $task = new Tasks\Fetch();
                $task->run();
                break;

            case 'proxy' :
                $task = new Tasks\Proxy();
                $task->run();
                break;

            case 'extension' :
                $task = new Tasks\Extension();
                $task->run();
                break;

            case 'baseline' :
                $task = new Tasks\Baseline();
                $task->run();
                break;

            case 'config' :
                $task = new Tasks\Config();
                $task->run();
                break;

            case 'show' :
                $task = new Tasks\Show();
                $task->run();
                break;

            case 'install' :
                $task = new Tasks\Install();
                $task->run();
                break;

            default :
                $command_value = $this->config->command_value;
                $suggestions = array_filter(array_keys(Commandline::$commands), function($x) use ($command_value) { similar_text($command_value, $x, $percentage); return $percentage > 60; });
                if (empty($suggestions)) {
                    print "Unknow command '{$this->config->command_value}'. See https://exakat.readthedocs.io/en/latest/Commands.html" . PHP_EOL;
                } else {
                    print "Unknow command '{$this->config->command_value}'. See https://exakat.readthedocs.io/en/latest/Commands.html" . PHP_EOL.
                          "Did you mean : ".implode(', ', $suggestions).' ? '.PHP_EOL;
                }
                // fallthrough

            case 'version' :
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

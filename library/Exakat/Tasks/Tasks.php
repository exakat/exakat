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


namespace Exakat\Tasks;

use Exakat\Config;
use Exakat\Datastore;
use Exakat\Exceptions\AnotherProcessIsRunning;
use Exakat\Log;

abstract class Tasks {
    protected $log        = null;
    protected $enabledLog = true;
    protected $datastore  = null;
    protected $gremlin    = null;
    protected $config     = null;
    protected $exakatDir  = null;
    private   static $semaphore      = null;
    private   static $keepSemaphore  = false;
    
    const  NONE    = 1;
    const  ANYTIME = 2;
    const  QUEUE = 4;
    const  SERVER = 5;
    
    public function __construct($gremlin) {
        $this->gremlin = $gremlin;
        // Config is the general one.
        $config = Config::factory();

        if (!defined('static::CONCURENCE')) {
            print get_class($this)." is missing CONCURENCE\n";
            die();
        }
        if (static::CONCURENCE !== self::ANYTIME) {
            if (self::$semaphore === null) {
                if (static::CONCURENCE === self::QUEUE) {
                    $ftok_proj = 'q';
                } elseif (static::CONCURENCE === self::SERVER) {
                    $ftok_proj = 's';
                } else {
                    $ftok_proj = 'j';
                }
                $key = ftok(__FILE__, $ftok_proj);
                self::$semaphore = sem_get($key, 1);
                if (sem_acquire(self::$semaphore, 1) === false) {
                    throw new AnotherProcessIsRunning();
                }
            } else {
                self::$keepSemaphore = true;
            }
        } 
                
        if ($this->enabledLog) {
            $task = strtolower((new \ReflectionClass($this))->getShortName());
            $this->log = new Log($task,
                                  $config->projects_root.'/projects/'.$config->project);
        }
        
        if ($config->project != 'default' &&
            file_exists($config->projects_root.'/projects/'.$config->project)) {
            $this->datastore = new Datastore($config);
        }

        if (!file_exists($config->projects_root.'/projects/.exakat/')) {
            mkdir($config->projects_root.'/projects/.exakat/', 0700);
        }
        $this->exakatDir = $config->projects_root.'/projects/.exakat/';
    }
    
    public function __destruct() {
        if (self::$keepSemaphore === false && self::$semaphore !== null) {
            sem_remove(self::$semaphore);
            self::$semaphore = null;
        }
    }
    
    protected function checkTokenLimit() {
        $nb_tokens = $this->datastore->getHash('tokens');

        $config = Config::factory();
        if ($nb_tokens > $config->token_limit) {
            $this->datastore->addRow('hash', array('token error' => "Project too large ($nb_tokens / {$config->token_limit})"));
            die("Project too large ($nb_tokens / {$config->token_limit})\n");
        }
    }
    
    public abstract function run(Config $config);

    protected function cleanLogForProject($project) {
        $logs = glob($this->config->projects_root.'/projects/'.$project.'/log/*');
        foreach($logs as $log) {
            unlink($log);
        }
    }

}

?>

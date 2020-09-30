<?php declare(strict_types = 1);
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

use Exakat\Config;
use Exakat\Exceptions\AnotherProcessIsRunning;
use Exakat\Exceptions\ProjectTooLarge;
use Exakat\Log;

abstract class Tasks {
    protected $log        = null;
    protected $logname    = self::LOG_AUTONAMING;
    protected $datastore  = null;

    protected $gremlin    = null;
    protected $config     = null;

    protected $is_subtask   = self::IS_NOT_SUBTASK;

    protected static $semaphore      = null;
    protected static $semaphorePort  = null;

    protected $rulesets = null;

    private $snitch = null;
    private $pid = 0;
    private $path = '';

    const  NONE    = 1;
    const  ANYTIME = 2;
    const  DUMP    = 3;
    const  QUEUE   = 4;
    const  SERVER  = 5;

    const IS_SUBTASK     = true;
    const IS_NOT_SUBTASK = false;

    const LOG_NONE = null;
    const LOG_AUTONAMING = '';

    public function __construct(bool $subTask = self::IS_NOT_SUBTASK) {
        $this->gremlin    = exakat('graphdb');
        $this->config     = exakat('config');
        $this->datastore  = exakat('datastore');
        $this->datastore->reuse();
        $this->is_subtask = $subTask;

        assert(defined('static::CONCURENCE'), get_class($this) . " is missing CONCURENCE\n");

        if (static::CONCURENCE !== self::ANYTIME && $subTask === self::IS_NOT_SUBTASK) {
            if (self::$semaphore === null) {
                if (static::CONCURENCE === self::QUEUE) {
                    self::$semaphorePort = $this->config->concurencyCheck;
                } elseif (static::CONCURENCE === self::SERVER) {
                    self::$semaphorePort = $this->config->concurencyCheck + 1;
                } elseif (static::CONCURENCE === self::DUMP) {
                    self::$semaphorePort = $this->config->concurencyCheck + 2;
                } else {
                    self::$semaphorePort = $this->config->concurencyCheck + 3;
                }

                if ($socket = @stream_socket_server('udp://0.0.0.0:' . self::$semaphorePort, $errno, $errstr, STREAM_SERVER_BIND)) {
                    self::$semaphore = $socket;
                } else {
                    throw new AnotherProcessIsRunning();
                }
            }
        }

        if ($this->logname === self::LOG_AUTONAMING) {
            $a = get_class($this);
            $this->logname = strtolower(substr($a, strrpos($a, '\\') + 1));
        }

        if ($this->logname !== self::LOG_NONE) {
            $this->log = new Log($this->logname,
                                 "{$this->config->projects_root}/projects/{$this->config->project}");
        }

        if ($this->config->inside_code === Config::INSIDE_CODE ||
            $this->config->project !== 'default') {
                if (!file_exists($this->config->tmp_dir) &&
                     file_exists(dirname($this->config->tmp_dir)) ) {
                    mkdir($this->config->tmp_dir, 0700);
            }
        } elseif (!file_exists("{$this->config->projects_root}/projects/")) {
            mkdir("{$this->config->projects_root}/projects/", 0700);
        }

        if ($this->config->project !== 'default') {
            $this->datastore = exakat('datastore');
        }

        $this->rulesets = exakat('rulesets');
    }

    public function __destruct() {
        if (static::CONCURENCE !== self::ANYTIME       &&
            $this->is_subtask === self::IS_NOT_SUBTASK &&
            !empty(self::$semaphore)) {
            fclose(self::$semaphore);
            self::$semaphore = null;
            self::$semaphorePort = -1;
        }
    }

    protected function checkTokenLimit(): void {
        $nb_tokens = $this->datastore->getHash('tokens');

        if ($nb_tokens > $this->config->token_limit) {
            $this->datastore->addRow('hash', array('token error' => "Project too large ($nb_tokens / {$this->config->token_limit})"));
            throw new ProjectTooLarge($nb_tokens, $this->config->token_limit);
        }
    }

    abstract public function run(): void;

    protected function cleanLogForProject(): void {
        $logs = glob("{$this->config->log_dir}/*");
        foreach($logs as $log) {
            unlink($log);
        }
    }

    protected function addSnitch(array $values = array()): void {
        if ($this->snitch === null) {
            $this->snitch = str_replace('Exakat\\Tasks\\', '', get_class($this));
            $this->pid = getmypid();
            $this->path = "{$this->config->tmp_dir}/{$this->snitch}.json";
        }

        $values['pid'] = $this->pid;
        file_put_contents($this->path, json_encode($values));
    }

    protected function removeSnitch(): void {
        if ($this->path !== null) {
            unlink($this->path);
        }
    }
}

?>

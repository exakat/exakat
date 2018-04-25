<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class Jobqueue extends Tasks {
    const CONCURENCE = self::QUEUE;
    const PATH = '/tmp/queue.exakat';
    
    const COMMANDS = array('quit', 'ping', 'project', 'onepage', 'report', 'init');

    private $pipefile = self::PATH;
    private $jobQueueLog = null;

    public function __destruct() {
        $this->log->log('Closed jobQueue');

        unlink($this->pipefile);
        fclose($this->jobQueueLog);

        parent::__destruct();
    }

    public function run() {
        if (!file_exists($this->config->projects_root.'/projects/log/')) {
            mkdir($this->config->projects_root.'/projects/log/', 0700);
        }
        $this->jobQueueLog = fopen($this->config->projects_root.'/projects/log/jobqueue.log', 'a');
        $this->log('Open Job Queue '.date('r')."\n");

        $this->log->log('Started jobQueue : '.time()."\n");

        $queue = array();

        if (!file_exists($this->config->projects_root.'/projects/onepage')) {
            mkdir($this->config->projects_root.'/projects/onepage/', 0755);
            mkdir($this->config->projects_root.'/projects/onepage/code', 0755);
            mkdir($this->config->projects_root.'/projects/onepage/log', 0755);
        }
        if (!file_exists($this->config->projects_root.'/projects/onepage/reports')) {
            mkdir($this->config->projects_root.'/projects/onepage/reports', 0755);
        }

        //////// setup our named pipe ////////
        // @todo put this in config
        print "Opening $this->pipefile\n";
        if(file_exists($this->pipefile)) {
            if(!unlink($this->pipefile)) {
                die('unable to remove existing PipeFile "'.$this->pipefile.'". Aborting.'."\n");
            }
        }

        umask(0);
        if(!posix_mkfifo($this->pipefile,0666)) {
            die('unable to create named pipe');
        }

        $pipe = fopen($this->pipefile,'r+');
        if(!$pipe) {
            die('unable to open the named pipe');
        }
        stream_set_blocking($pipe, false);
        
        $commandRegex = '/^('.implode('|', self::COMMANDS).') /';

        //////// process the queue ////////
        $round = 0;
        while(1) {
            while($input = trim(fgets($pipe))) {
                stream_set_blocking($pipe, false);
                $queue[] = $input;
            }

            $job = current($queue);
            $jobkey = key($queue);
            
            if(!empty($job)) {
                
                $command = json_decode(trim($job));
                
                if ($command === null) {
                    $this->log('Unknown command : '.$job."\t".time()."\n");
                    next($queue);
                    unset($job, $queue[$jobkey]);
                    continue;
                }
                
                $command = array_merge(['exakat'], $command);
                if ($command[1] === 'init') {
                    $this->processInit($command);
                } elseif ($command[1] === 'project') {
                    $this->processProject($command);
                } elseif ($command[1] === 'report') {
                    $this->processReport($command);
                } elseif ($command[1] === 'remove') {
                    $this->processRemove($command);
                } else {
                    print "Unknown command '".$command[1].'"'.PHP_EOL;
                }
//                list($job, $args) = explode(' ', $job);
//                print "$job / $args\n";

//                $method = 'process'.$job;
//                $this->$method($job);

                next($queue);
                unset($job, $queue[$jobkey]);
            } else {
                display( 'no jobs to do - waiting...'.PHP_EOL);
                stream_set_blocking($pipe, true);
            }
        }
    }
    
    private function processQuit($job) {
        display( "Received quit command. Bye\n");
        $this->log('Quit command');
        $this->log->log('Quit jobQueue : '.time()."\n");
        die();
    }
    
    private function processInit($job) {
        $config = new Config($job);
        $analyze = new Initproject($this->gremlin, $config, Tasks::IS_SUBTASK);

        display( 'processing init job '.$job.PHP_EOL);
        $this->log('start init : '.$job);
        $b = microtime(true);
        $analyze->run();
        $e = microtime(true);
        $this->log('end init : '.$job[1].' ('.number_format($e -$b, 2).' s)');
        unset($analyze);
        display( 'processing init job '.$job[1].' done ('.number_format($e -$b, 2).' s)'.PHP_EOL);
    }
    
    private function processPing($job) {
        print 'pong'.PHP_EOL;
    }

    private function processReport($job) {
        $config = new Config($job);
        $analyze = new Report2($this->gremlin, $config, Tasks::IS_SUBTASK);

        display( 'processing report job '.$job.PHP_EOL);
        $this->log('start report : '.$job[1]);
        $b = microtime(true);
        $analyze->run();
        $e = microtime(true);
        unset($analyze);
        display( 'processing report job '.$job[1].' done ('.number_format($e -$b, 2).' s)'.PHP_EOL);
    }

    private function processProject($job) {
        $config = new Config($job);
        $analyze = new Project($this->gremlin, $config, Tasks::IS_SUBTASK);

        display( 'processing project job '.$job.PHP_EOL);
        $this->log('start project : '.$job);
        $b = microtime(true);
        $analyze->run();
        $e = microtime(true);
        $this->log('end project : '.$job[1].' ('.number_format($e -$b, 2).' s)');
        unset($analyze);
        display( 'processing project job '.$job[1].' done ('.number_format($e -$b, 2).' s)'.PHP_EOL);
    }

    private function processRemove($job) {
        $config = new Config($job);
        $analyze = new Remove($this->gremlin, $config, Tasks::IS_SUBTASK);

        display( 'processing remove job '.$job.PHP_EOL);
        $this->log('start report : '.$job[1]);
        $b = microtime(true);
        $analyze->run();
        $e = microtime(true);
        unset($analyze);
        display( 'processing remove job '.$job[1].' done ('.number_format($e -$b, 2).' s)'.PHP_EOL);
    }

    private function log($message) {
        fwrite($this->jobQueueLog, date('r')."\t".$message."\n");
    }
}

?>

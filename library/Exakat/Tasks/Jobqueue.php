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
    const PATH = '/tmp/onepageQueue';

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

        //////// process the queue ////////
        while(1) {
            while($input = trim(fgets($pipe))) {
                stream_set_blocking($pipe, false);
                $queue[] = $input;
            }

            $job = current($queue);
            $jobkey = key($queue);

            if($job) {
                switch($job) {
                    case 'quit' :
                        display( "Received quit command. Bye\n");
                        $this->log('Quit command');
                        $this->log->log('Quit jobQueue : '.time()."\n");
                        die();

                    case 'ping' :
                        print 'pong'.PHP_EOL;
                        break;

                    case file_exists($this->config->projects_root.'/projects/'.$job) :
                        display( 'processing project job '.$job.PHP_EOL);
                        if (file_exists($this->config->projects_root.'/projects/'.$job.'/dump.sqlite')) {
                            $this->log('omitting project ready : '.$job);
                            break;
                        }
                        $this->log('start project : '.$job);
                        $b = microtime(true);
                        shell_exec($this->config->php.' '.$this->config->executable.' project -p '.$job);
                        $e = microtime(true);
                        $this->log('end project : '.$job.' ('.number_format($e -$b, 2).' s)');
                        display( 'processing project job '.$job.' done ('.number_format($e -$b, 2).' s)'.PHP_EOL);
                        break;

                    case file_exists($this->config->projects_root.'/projects/onepage/code/'.$job.'.php') :
                        display( 'processing onepage job '.$job.PHP_EOL);
                        $this->process($job);
                        break;

                    default :
                        display('Default order '.$job.'. Ignoring '.PHP_EOL);
                }

                next($queue);
                unset($job, $queue[$jobkey]);
            } else {
                display( 'no jobs to do - waiting...'.PHP_EOL);
                stream_set_blocking($pipe, true);
            }
        }
    }

    private function process($job) {
        $this->log->log('started onepage : '.$job."\n");
        $b = microtime(true);

        // This has already been processed
        if (file_exists($this->config->projects_root.'/projects/onepage/reports/'.$job.'.json')) {
            display( "$job already exists\n");
            return;
        }

        file_put_contents($this->config->projects_root.'/progress/jobqueue.exakat', json_encode(array('start' => time(), 'job' => $job, 'progress' => 0)));
        shell_exec($this->config->php.' '.$this->config->executable.' onepage -f '.$this->config->projects_root.'/projects/onepage/code/'.$job.'.php');

        // final progress
        $progress = json_decode(file_get_contents($this->config->projects_root.'/progress/jobqueue.exakat'));
        $progress->end = time();
        file_put_contents($this->config->projects_root.'/progress/jobqueue.exakat', json_encode($progress));

        $e = microtime(true);
        $this->log('end onepage : '.$job.' ('.number_format($e -$b, 2).' s)');

        // Clean after self
        shell_exec($this->config->php.' '.$this->config->executable.' cleandb');
    }

    private function log($message) {
        fwrite($this->jobQueueLog, date('r')."\t".$message."\n");
    }
}

?>

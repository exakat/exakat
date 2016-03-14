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


namespace Tasks;

class Jobqueue extends Tasks {
    private $config   = null;
    private $pipefile = '/tmp/onepageQueue';
    
    public function __destruct() {
        $this->log->log('Closed jobQueue : '.time()."\n");
        
        unlink($this->pipefile);
    }
    
    public function run(\Config $config) {
        $this->config = $config;
        
        $this->log->log('Started jobQueue : '.time()."\n");

        $queue = array();
        
        // @todo add this to doctor
        if (!file_exists($this->config->projects_root.'/out')) {
            mkdir($this->config->projects_root.'/out', 0755);
        }
        if (!file_exists($this->config->projects_root.'/in')) {
            mkdir($this->config->projects_root.'/in', 0755);
        }
        if (!file_exists($this->config->projects_root.'/progress')) {
            mkdir($this->config->projects_root.'/progress', 0755);
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
                        $this->log->log('Quit jobQueue : '.time()."\n");
                        die();

                    default:
                        if (file_exists($this->config->projects_root.'/in/'.$job.'.php')) {
                            display( 'processing onepage job ' . $job . PHP_EOL);
                            $this->process($job);
                        } elseif (file_exists($this->config->projects_root.'/projects/'.$job)) {
                            display( 'processing project job ' . $job . microtime(true). PHP_EOL);
                            
                            shell_exec($this->config->php.' '.$this->config->executable.' project -p '.$job);
                        }
                    }
                    
                    next($queue);
                    unset($job, $queue[$jobkey]);
                } else {
                display( 'no jobs to do - waiting...'. PHP_EOL);
                stream_set_blocking($pipe, true);
            }
        }
    }

    private function process($job) {
        $this->log->log('Started : ' . $job.' '.time()."\n");

        // This has already been processed
        if (file_exists($this->config->projects_root.'/out/'.$job.'.json')) {
            display( "$job already exists\n");
            return;
        }

        file_put_contents($this->config->projects_root.'/progress/jobqueue.exakat', json_encode(['start' => time(), 'job' => $job, 'progress' => 0]));
        shell_exec($this->config->php.' '.$this->config->executable.' onepage -f '.$this->config->projects_root.'/in/'.$job.'.php');

        // cleaning
        rename($this->config->projects_root.'/projects/onepage/onepage.json', $this->config->projects_root.'/out/'.$job.'.json');
        
        // final progress
        $progress = json_decode(file_get_contents($this->config->projects_root.'/progress/jobqueue.exakat'));
        $progress->end = time();
        file_put_contents($this->config->projects_root.'/progress/jobqueue.exakat', json_encode($progress));

        $this->log->log('Finished : ' . $job.' '.time()."\n");

        // Clean after self
        shell_exec($this->config->php.' '.$this->config->executable.' cleandb');

        return true;
    }

    private function processProject($job) {
        $this->log->log('Started Project : ' . $job.' '.time()."\n");

        // This has already been processed
        if (file_exists($this->config->projects_root.'/projects/'.$job.'/report')) {
            display( "$job has already a report (/projects/$job/report). Remove it first. Aborting\n");
            return;
        }

        file_put_contents($this->config->projects_root.'/progress/jobqueue.exakat', json_encode(['start' => time(), 'job' => $job, 'progress' => 0]));
        shell_exec($this->config->php.' '.$this->config->executable.' project -p '.$job);

        // cleaning
        $progress = json_decode(file_get_contents($this->config->projects_root.'/progress/jobqueue.exakat'));
        $progress->end = time();
        file_put_contents($this->config->projects_root.'/progress/jobqueue.exakat', json_encode($progress));

        $this->log->log('Finished Project : ' . $job.' '.time()."\n");

        shell_exec($this->config->php.' '.$this->config->executable.' cleandb');

        return true;
    }
}

?>

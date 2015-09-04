<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
    private $log = null;
    private $config = null;
    
    public function __destruct() {
        if (!feof($this->log)) {
            fwrite($this->log, 'Closed jobQueue : '.time()."\n");
            fclose($this->log);
        }
    }
    
    public function run(\Config $config) {
        $this->config = $config;
        
        $this->log = fopen($config->projects_root.'/log/jobqueue.log', 'a');
        fwrite($this->log, 'Started jobQueue : '.time()."\n");

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
        $pipefile = '/tmp/onepageQueue';
        if(file_exists($pipefile)) {
            if(!unlink($pipefile)) {
                die('unable to remove existing PipeFile "'.$pipefile.'". Aborting.'."\n");
            }
        }
        
        umask(0);
        if(!posix_mkfifo($pipefile,0666)) {
            die('unable to create named pipe');
        }

        $pipe = fopen($pipefile,'r+');
        if(!$pipe) {
            die('unable to open the named pipe');
        }
        stream_set_blocking($pipe,false);

        //////// process the queue ////////
        while(1) {
            while($input = trim(fgets($pipe))) {
                stream_set_blocking($pipe,false);
                $queue[] = $input;
            }

            $job = current($queue);
            $jobkey = key($queue);
            
            if($job) {
                switch($job) {
                    case 'quit' :
                        print "Received quit command. Bye\n";
                        fwrite($this->log, 'Quit jobQueue : '.time()."\n");
                        die();

                    default:
                        if (file_exists($this->config->projects_root.'/in/'.$job.'.php')) {
                            print 'processing onepage job ' . $job . PHP_EOL;
                            $this->process($job);
                        } else {
                            print 'processing project ' . $job . PHP_EOL;
                            $this->processProject($job);
                        }
                }
        
                next($queue);
                unset($job,$queue[$jobkey]);
            } else {
                print 'no jobs to do - waiting...'. PHP_EOL;
                stream_set_blocking($pipe,true);
            }
        }
    }

    private function process($job) {
        fwrite($this->log, 'Started : ' . $job.' '.time()."\n");

        // This has already been processed
        if (file_exists($this->config->projects_root.'/out/'.$job.'.json')) {
            print "$job already exists\n";
            return;
        }

        file_put_contents($this->config->projects_root.'/progress/jobqueue.exakat', json_encode(['start' => time(), 'job' => $job]));
        shell_exec('php '.$this->config->executable.' onepage -f '.$this->config->projects_root.'/in/'.$job.'.php -p onepage');

        // cleaning
        rename($this->config->projects_root.'/projects/onepage/onepage.json', $this->config->projects_root.'/out/'.$job.'.json');
        $progress = json_decode(file_get_contents($this->config->projects_root.'/progress/jobqueue.exakat'));
        $progress->end = time();
        file_put_contents($this->config->projects_root.'/progress/jobqueue.exakat', json_encode($progress));

        fwrite($this->log, 'Finished : ' . $job.' '.time()."\n");

        shell_exec('php '.$this->config->executable.' cleandb');

        return true;
    }

    private function processProject($job) {
        fwrite($this->log, 'Started Project : ' . $job.' '.time()."\n");

        // This has already been processed
        if (file_exists($this->config->projects_root.'/projects/'.$job.'/report')) {
            print "$job has already a report (/projects/$job/report). Remove it first. Aborting\n";
            return;
        }

        file_put_contents($this->config->projects_root.'/progress/jobqueue.exakat', json_encode(['start' => time(), 'job' => $job]));
        shell_exec('php '.$this->config->executable.' project -p '.$job);

        // cleaning
        $progress = json_decode(file_get_contents($this->config->projects_root.'/progress/jobqueue.exakat'));
        $progress->end = time();
        file_put_contents($this->config->projects_root.'/progress/jobqueue.exakat', json_encode($progress));

        fwrite($this->log, 'Finished Project : ' . $job.' '.time()."\n");

        shell_exec('php '.$this->config->executable.' cleandb');

        return true;
    }
}

?>

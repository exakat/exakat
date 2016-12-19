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
use Exception;

class CleanDb extends Tasks {
    const CONCURENCE = self::ANYTIME;
    
    public function __construct($gremlin, $config, $subtask = Tasks::IS_NOT_SUBTASK) {
        $this->enabledLog = false;
        parent::__construct($gremlin, $config, $subtask);
    }
    
    public function run() {
        if ($this->config->quick) {
            $this->restartNeo4j();
            $this->cleanScripts();
            return false;
        }

        $queryTemplate = <<<GREMLIN
g.V().count();
GREMLIN;
        $result = null;
        $counts = 0;
        
        while($counts < 100 && (!is_object($result) || $result->results === null)) {
            $result = $this->gremlin->query($queryTemplate);
            ++$counts;
            usleep(100000);
        }

        if ($counts === 100)  {
            display('No connexion to neo4j : forcing restart ('.$counts.')');
            // Can't connect to neo4j. Forcing restart.
            $this->restartNeo4j();
            $this->cleanScripts();
            return false;
        } else {
            display('Connexion to neo4j found ('.$counts.')');
        }
        $nodes = $result->results[0];
        display($nodes.' nodes in the database');

        $begin = microtime(true);
        if ($nodes == 0) {
            display('No nodes in neo4j. No need to clean');
        } elseif ($nodes > 10000) {
            display($nodes.'nodes : forcing restart');
            $this->restartNeo4j();
        } else {
            display('Cleaning with gremlin');
        
            $queryTemplate = <<<GREMLIN

g.E().drop();
g.V().drop();

GREMLIN;
            $this->gremlin->query($queryTemplate);
            display('Database cleaned');
        }
        $end = microtime(true);
        display(number_format(($end - $begin) * 1000, 0).' ms');
        
        $this->cleanScripts();
        $this->cleanTmpDir();
    }
    
    private function cleanScripts() {
        display('Cleaning scripts');
        $files = glob($this->config->neo4j_folder.'/scripts/a*.gremlin');
        foreach($files as $file) {
            unlink($file);
        }
        display('   Cleaned '.count($files).' gremlin scripts');
    }

    private function cleanTmpDir() {
        rmdirRecursive($this->config->projects_root.'/projects/.exakat/');
        mkdir($this->config->projects_root.'/projects/.exakat/');
    }
    
    private function restartNeo4j() {
        display('Cleaning with restart');
        $this->config = $this->config;
        
        // preserve data/dbms/auth to preserve authentication
        if (file_exists($this->config->neo4j_folder.'/data/dbms/auth')) {
            $sshLoad =  'mv data/dbms/auth ../auth; rm -rf data; mkdir -p data/dbms; mv ../auth data/dbms/auth; mkdir -p data/log; mkdir -p data/scripts ';
        } else {
            $sshLoad =  'rm -rf data; mkdir -p data; mkdir -p data/log; mkdir -p data/scripts ';
        }

        // if neo4j-service.pid exists, we kill the process once
        if (file_exists($this->config->neo4j_folder.'/data/neo4j-service.pid')) {
            shell_exec('kill -9 $(cat '.$this->config->neo4j_folder.'/data/neo4j-service.pid) 2>>/dev/null; ');
        }
        
        shell_exec('cd '.$this->config->neo4j_folder.'; '.$sshLoad);

        if (!file_exists($this->config->neo4j_folder.'/conf/')) {
            print "No conf folder in {$this->config->neo4j_folder}\n";
        } elseif (!file_exists($this->config->neo4j_folder.'/conf/neo4j-server.properties')) {
            print "No neo4j-server.properties file in {$this->config->neo4j_folder}/conf/\n";
        } else {
            $neo4j_config = file_get_contents($this->config->neo4j_folder.'/conf/neo4j-server.properties');
            if (preg_match('/org.neo4j.server.webserver.port *= *(\d+)/m', $neo4j_config, $r)) {
                if ($r[1] != $this->config->neo4j_port) {
                    print "Warning : Exakat's port and Neo4j's port are not the same ($r[1] / {$this->config->neo4j_port})\n";
                }
            }
        }
        
        // checking that the server has indeed restarted
        if (Tasks::$semaphore !== null) {
            fclose(Tasks::$semaphore);
            $this->doRestart();
            Tasks::$semaphore = @stream_socket_server("udp://0.0.0.0:".Tasks::$semaphorePort, $errno, $errstr, STREAM_SERVER_BIND);
        } else {
            $this->doRestart();
        }

        display('Database cleaned with restart');

        try {
            $res = $this->gremlin->serverInfo();
            display('Restarted Neo4j cleanly');
        } catch (Exception $e) {
            display('Didn\'t restart neo4j cleanly');
        }

        $this->gremlin->query("g.addV('delete', true)");
    }
    
    private function doRestart() {
        $round = 0;
        do {
            ++$round;
            if ($round > 0) {
                sleep($round);
            }

            if ($round > 10) {
                if (file_exists($this->config->neo4j_folder.'/data/neo4j-service.pid')) {
                    $pid = file_get_contents($this->config->neo4j_folder.'/data/neo4j-service.pid');
                    die('Couldn\'t restart neo4j\'s server. Please, kill it (kill -9 '.$pid.') and try again');
                } else {
                    die('Couldn\'t restart neo4j\'s server, though it doesn\'t seem to be running. Please, make sure it is runnable at "'.$this->config->neo4j_folder.'" and try again.');
                }
            }
            
            echo exec('cd '.$this->config->neo4j_folder.'; ./bin/neo4j start >/dev/null 2>&1 & ');
            
            // Might be : Another server-process is running with [49633], cannot start a new one. Exiting.
            // Needs to pick up this error and act
            // also, may be we can wait for the pid to appear?

            $res = $this->gremlin->serverInfo();
        } while ( $res === false);
    }
}

?>

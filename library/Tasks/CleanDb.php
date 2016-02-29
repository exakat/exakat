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

class CleanDb extends Tasks {
    private $config = null;

    public function __construct() {
        $this->enabledLog = false;
        parent::__construct();
    }
    
    public function run(\Config $config) {
        $this->config = $config;

        if (!file_exists($config->neo4j_folder.'/scripts/exakat.txt')) {
            display('Warning : This Neo4j installation doesn\'t seem to be used by Exakat. Please, clean it manually and run "doctor".');
            return false;
        }
        
        if ($config->quick) {
            $this->restartNeo4j();
            $this->cleanScripts();
            return false;
        }

        $queryTemplate = <<<GREMLIN
g.V.count();
GREMLIN;
        $result = gremlin_query($queryTemplate);
        if (!is_object($result) || $result->results === null) {
            // Can't connect to neo4j. Forcing restart.
            $this->restartNeo4j();
            $this->cleanScripts();
            return false;
        }
        $nodes = $result->results[0];
        display($nodes.' nodes in the database');

        $begin = microtime(true);
        if ($nodes == 0) {
            display('No nodes in neo4j. No need to clean');
        } elseif ($nodes > 10000) {
            $this->restartNeo4j();
        } else {
            display('Cleaning with gremlin');
        
            $queryTemplate = <<<GREMLIN

g.E.each{ g.removeEdge(it); }
g.V.each{ g.removeVertex(it); } 

GREMLIN;
            gremlin_query($queryTemplate);
            display('Database cleaned');
        }
        $end = microtime(true);
        display(number_format(($end - $begin) * 1000, 0).' ms');
        
        $this->cleanScripts();
    }
    
    private function cleanScripts() {
        display('Cleaning scripts');
        $files = glob($this->config->neo4j_folder.'/scripts/a*.gremlin');
        foreach($files as $file) {
            unlink($file);
        }
        display('   Cleaned '.count($files).' gremlin scripts');
    }
    
    private function restartNeo4j() {
        display('Cleaning with restart');
        $config = $this->config;
        
        // preserve data/dbms/auth to preserve authentication
        if (file_exists($config->neo4j_folder.'/data/dbms/auth')) {
            $sshLoad =  'mv data/dbms/auth ../auth; rm -rf data; mkdir -p data/dbms; mv ../auth data/dbms/auth; ';
        } else {
            $sshLoad =  'rm -rf data; mkdir -p data; ';
        }

        if (file_exists($config->neo4j_folder.'/data/neo4j-service.pid')) {
            shell_exec('cd '.$config->neo4j_folder.';kill -9 $(cat data/neo4j-service.pid) 2>>/dev/null; '.$sshLoad);
        } else {
            shell_exec('cd '.$config->neo4j_folder.'; '.$sshLoad);
        }
        
        // checking that the server has indeed restarted
        $round = 0;
        do {
            ++$round;
            if ($round > 0) {
                sleep($round);
            }
            shell_exec('cd '.$config->neo4j_folder.'; ./bin/neo4j start-no-wait 2>&1');
            
            // Might be : Another server-process is running with [49633], cannot start a new one. Exiting.
            // Needs to pick up this error and act
            // also, may be we can wait for the pid to appear?

            $res = neo4j_serverInfo();
        } while ( $res === false);
        
        display('Database cleaned with restart');

        try {
            neo4j_serverInfo();
            display('Restarted Neo4j cleanly');
        } catch (\Exception $e) {
            display('Didn\'t restart neo4j cleanly');
        }
        
    }
}

?>

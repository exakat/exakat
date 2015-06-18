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

use Everyman\Neo4j\Client,
    Everyman\Neo4j\Index\NodeIndex,
    Everyman\Neo4j\Relationship,
    Everyman\Neo4j\Node,
    Everyman\Neo4j\Cypher\Query;

class CleanDb implements Tasks {
    private $client = null;
    private $config = null;
    
    public function run(\Config $config) {
        $this->config = $config;
        $client = $this->getClient();
        
        $queryTemplate = <<<CYPHER
start n=node(*)
match n
return count(n)
CYPHER;
        $query = new Query($client, $queryTemplate, array());
        $result = $query->getResultSet();
        $nodes = $result[0][0];
        display($nodes.' nodes in the database');

        $begin = microtime(true);
        if ($nodes == 0) {
            display('No nodes in neo4j. No need to clean');
        } elseif ($config->quick || $nodes > 10000) {
            display('Cleaning with restart');
            shell_exec('cd '.$config->projects_root.'/neo4j/;sh ./bin/neo4j stop; rm -rf data; mkdir data');
            
            // checking that the server has indeed restarted
            $round = 0;
            do {
                $round++;
                if ($round > 0) {
                    sleep($round);
                }
                shell_exec('cd '.$config->projects_root.'/neo4j/;sh ./bin/neo4j start-no-wait 2>&1');

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'http://'.$config->neo4j_host);
                curl_setopt($ch, CURLOPT_PORT, $config->neo4j_port);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $res = curl_exec($ch);
                curl_close($ch);
            } while ( $res === false);
            
            display('Database cleaned with restart');

            try {
                $client = new Client();
                $client->getServerInfo();
                display('Restarted Neo4j cleanly');
            } catch (\Exception $e) {
                display('Didn\'t restart neo4j cleanly');
            }
        } else {
            display('Cleaning with cypher');
        
            $queryTemplate = 'MATCH (n)
OPTIONAL MATCH (n)-[r]-()
DELETE n,r';
            $query = new Query($client, $queryTemplate, array());
            $result = $query->getResultSet();
            display('Database cleaned');
        }
        $end = microtime(true);
        display(number_format(($end - $begin) * 1000, 0).' ms');
    }
    
    private function getClient() {
        try {
            $client = new Client();
            $client->getServerInfo();
        } catch (\Exception $e) {
            display('Couldn\'t access Neo4j');
            shell_exec('cd '.$this->config->projects_root.'/neo4j;sh ./bin/neo4j start');

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'http://'.$this->config->neo4j_host);
                curl_setopt($ch, CURLOPT_PORT, $this->config->neo4j_port);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $res = curl_exec($ch);
                curl_close($ch);

            sleep(1);
            
            $this->getClient();
        }

        return $client;
    }
}

?>

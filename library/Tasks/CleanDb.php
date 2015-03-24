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
    
    public function run(\Config $config) {
        try {
            $client = new Client();
            $client->getServerInfo();
        } catch (\Exception $e) {
            display("Couldn't access Neo4j\n");
            shell_exec('cd neo4j; ./bin/neo4j start');
        }
        
        $queryTemplate = 'start n=node(*)
match n
return count(n)';
        $query = new Query($client, $queryTemplate, array());
        $result = $query->getResultSet();
        $nodes = $result[0][0];
        display($nodes." nodes in the database\n");

        $begin = microtime(true);
        if ($nodes == 0) {
            display( "No nodes in neo4j. No need to clean\n");
        } elseif ($config->quick || $nodes > 10000) {
            display("Cleaning with restart\n");
            shell_exec('cd '.$config->projects_root.'/neo4j/;./bin/neo4j stop; rm -rf data; mkdir data; mkdir data/log');
            shell_exec('cd '.$config->projects_root.'/neo4j/;./bin/neo4j start');
            display("Database cleaned with restart\n");

            try {   
                $client = new Client();
                $client->getServerInfo();
                display( "Restarted Neo4j cleanly \n");
            } catch (\Exception $e) {
                display( " Didn't restart neo4j cleanly\n");
            }
        } else {
            display("Cleaning with cypher\n");
        
            $queryTemplate = 'MATCH (n) 
OPTIONAL MATCH (n)-[r]-() 
DELETE n,r';
        	$query = new Query($client, $queryTemplate, array());
	        $result = $query->getResultSet();
            display("Database cleaned\n");
        }
        $end = microtime(true);
        display(number_format(($end - $begin) * 1000, 0)." ms\n");
    }
}

?>
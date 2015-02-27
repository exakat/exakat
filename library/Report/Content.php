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


namespace Report;

class Content {
    protected $name    = 'Content'; 
    protected $project = null;
    protected $neo4j   = null;
    protected $array   = array();
    
    public function setNeo4j($client) {
        $this->neo4j = $client;
    }

    public function setProject($project) {
        $this->project = $project;
    }
    
    public function getHash() {
        return $this->hash;
    }
    
    public function getArray() {
        return $this->array;
    }

    public function query($query) {
        $params = array('type' => 'IN');
        try {
            $result = new \Everyman\Neo4j\Gremlin\Query($this->neo4j, $query, $params);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $message = preg_replace('#^.*\[message\](.*?)\[exception\].*#is', '\1', $message);
            print "Exception : ".$message."\n";
        
            print $query."\n";
            die(__METHOD__);
        }
        return $result->getResultSet();
    }
}

?>

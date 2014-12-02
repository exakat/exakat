<?php

namespace Report;

class Content {
    protected $name    = 'Content'; 
    protected $project = null;
    protected $neo4j   = null;
    protected $mysql   = null;
    
    public function setNeo4j($client) {
        $this->neo4j = $client;
    }

    public function setDb($db) {
        $this->db = $db;
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
}

?>
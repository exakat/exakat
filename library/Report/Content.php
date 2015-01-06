<?php

namespace Report;

class Content {
    protected $name    = 'Content'; 
    protected $project = null;
    protected $neo4j   = null;
    protected $mysql   = null;
    protected $array   = array();
    
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

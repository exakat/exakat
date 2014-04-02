<?php

namespace Report\Content;

class Appinfo extends \Report\Content {
    private $list = array();

    public function collect() {
//        $this->list['Number of lines of code'] = $vertices[0][0];
    }
    
    public function setNeo4j($client) {
        $this->neo4j = $client;
    }

    public function toArray() {
        return $this->list;
    }
}

?>
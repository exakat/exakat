<?php

namespace Report\Content;

class Groupby {
    private $analyzers = array();
    private $client = null;
    private $name = "Unnamed Group By";

    public function __construct($client) {
        $this->client = $client;
    }
    
    public function addAnalyzer($analyzer) {
        if (is_array($analyzer)) {
            $this->analyzers = array_merge($this->analyzers, $analyzer);
        } else {
            $this->analyzers[] = $analyzer;
        }
    }

    public function setGroupBy($method) {
        $this->method = $method;
    }

    public function setCount($count) {
        $this->count = $count;
    }

    public function toArray() {
        $array = array();
        
        foreach($this->analyzers as $a) {
            $analyzer = \Analyzer\Analyzer::getInstance($a, $this->client);
            $m = $this->method;
            $c = $this->count;
            
            @$array[$analyzer->$m()] += $analyzer->$c();
        }
        
        return $array;
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}

?>
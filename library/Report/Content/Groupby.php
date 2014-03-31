<?php

namespace Report\Content;

class Groupby {
    private $analyzers = array();
    private $client = null;
    private $name = "Unnamed Group By";

    private $sort = \Report\Content\Groupby::SORT_NONE;

    const SORT_NONE = 0;
    const SORT_VALUES = 1;
    const SORT_RVALUES = 2;
    const SORT_KEYS = 3;
    const SORT_RKEYS = 4;
    const SORT_RANDOM_VALUES = 5;
    const SORT_RANDOM_KEYS = 6;
    const SORT_ARBITRARY = 7;
    
    public function __construct($client) {
        $this->client = $client;
    }

    public function setSort($sort) {
        $this->sort = $sort;
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
        
        $this->sort_array($array);
        
        return $array;
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    private function sort_array(&$array) {
        if (is_array($this->sort)) {
            $sort = static::SORT_ARBITRARY;
        } else {
            $sort = (int) $this->sort;
        }

        switch($sort) {
            case static::SORT_VALUES : 
                asort($array);
                break 1;

            case static::SORT_RVALUES : 
                arsort($array);
                break 1;

            case static::SORT_KEYS : 
                ksort($array);
                break 1;

            case static::SORT_RKEYS : 
                krsort($array);
                break 1;

            case static::SORT_RANDOM_VALUES : 
            case static::SORT_RANDOM_KEYS : 
                $this->shuffle_assoc($array);
                break 1;

            case static::SORT_ARBITRARY : 
                $new = array();
                
                foreach($this->sort as $key) {
                    if (isset($array[$key])) {
                        $new[$key] = $array[$key];
                        unset($array[$key]);
                    } 
                }

                foreach($array as $key => $value) {
                    $new[$key] = $value;
                }
                
                $array = $new;
                
                break 1;

            default : // AKA SORT_NONE or all unknown values
        }
        
        return true;
    }

    private function shuffle_assoc(&$array) {
        $keys = array_keys($array);

        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }

        $array = $new;

        return true;
    }
}

?>
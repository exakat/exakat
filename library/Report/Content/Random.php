<?php

namespace Report\Content;

class Random {
    private $type = 'Hash';
    
    public function setType($type) {
        $this->type = $type;
    }

    public function getName() {
        return "Random Content";
    }

    public function getDescription() {
        return "Random Description";
    }

    public function toArray() {
        $array = array();
        foreach(range('a', 'f') as $k => $v) {
            $array[$v] = rand(1, 10);
        }
        
        return $array;
    }
}

?>
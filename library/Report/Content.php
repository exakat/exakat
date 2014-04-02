<?php

namespace Report;

class Content {
    protected $name = 'Content'; 
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

}

?>
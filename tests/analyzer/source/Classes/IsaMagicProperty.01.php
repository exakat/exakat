<?php

class uselessProperty {
    private $au;
    
    function foo() {
        $this->au;
    }
}

class magicReadProperty {
    public $br;
    
    function __get($name) {
        
    }

    function foo() {
        $this->ar;
        $this->br;
    }
}

class magicWriteProperty {
    public $bw;
    
    function __set($name, $value) {
        
    }

    function foo() {
        $this->aw = 1;
        $this->bw = 2;
    }
}
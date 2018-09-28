<?php

class uselessProperty {
    private $au;
    
    function foo($c) {
        $this->au;
        $c->au;
    }
}

class magicReadProperty {
    public $br;
    
    function __get($name) {
        
    }

    function foo($c) {
        $this->ar;
        $this->br;
        $c->ar;
        $c->br;
    }
}

class magicWriteProperty {
    public $bw;
    
    function __set($name, $value) {
        
    }

    function foo($c) {
        $this->aw = 1;
        $this->bw = 2;

        $c->aw = 1;
        $c->bw = 2;
    }
}
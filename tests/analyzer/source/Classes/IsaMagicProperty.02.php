<?php

class uselessProperty {
    private $au;
    
    function foo() {
        echo $this->au;
    }
}

class magicReadProperty {
    public $br;
    
    function __get($name) {
        
    }
}

class magicReadProperty2 extends magicReadProperty {
    function foo() {
        echo $this->ar;
        echo $this->br;
    }
}

class magicWriteProperty {
    public $bw;
    
    function __set($name, $value) {
        
    }
}

class magicWriteProperty2 extends magicWriteProperty {
    function foo() {
        $this->aw = 1;
        $this->bw = 2;
    }
}
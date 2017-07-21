<?php

class c {
    private $a;
    private $o = array();

    function __get($name) {
        return $this->o[$name];
    }
    
    function __set($name, $value) {
        $this->o[$name] = $value;
    }
    
    function foo() {
        $this->a['a'] = 3;
        $this->b['a'] = 3;
        $this->c[] = 3;
        
        print_r($this);
    }
}

$c = new c();
$c->foo();
?>
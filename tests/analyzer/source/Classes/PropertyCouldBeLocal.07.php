<?php

trait t {
    private $foo2 = 1;

    // Magic method, and constructor in particular, are omitted.
    function __construct($foo) {
        $this->foo = $foo;
    }
    
    function bar() {
        $this->foo2++;
        
        return $this->foo2;
    }

    function barbar() {}
}

trait ty {
    private $foo3 = 1;

    // Magic method, and constructor in particular, are omitted.
    function __construct($foo) {
        $this->foo3 = $foo;
    }
    
    function bar() {
        $this->foo3++;
        
        return $this->foo3;
    }
}
?>
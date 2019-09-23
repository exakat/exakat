<?php
class x {
    private $foo = 1;

    // Magic method, and constructor in particular, are omitted.
    function __construct($foo) {
        $this->foo = $foo;
    }
    
    function bar() {
        $this->foo++;
        
        return $this->foo;
    }

    function barbar() {}
}

class xy {
    private $foo1 = 1;

    // Magic method, and constructor in particular, are omitted.
    function __construct($foo) {
        $this->foo1 = $foo;
    }
    
    function bar() {
        $this->foo1++;
        
        return $this->foo;
    }
}

?>
<?php

// For testing purpose, this must be before the definition
// Otherwise, it won't lint 

class x {
    function bar() {
        $this->foo($a, $b);
        $this->foo($a, $b[1]);
        $this->foo($a, $b->d);
        $this->foo(1, 2);
        $this->foo(1, C);
        $this->foo(1, array());
        $this->foo(1, x());
    }


    function foo(&$a, $b) {
    
    }
}

?>
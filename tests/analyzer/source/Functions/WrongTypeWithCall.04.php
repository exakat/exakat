<?php

class x {
    function foo(X $x) {} 
    function foo2(string $o) {} 
    
    function bar() {
        $this->foo('d');
        $this->foo(new X);

        $this->foo2('e');
        $this->foo2(new D);
    }
}

?>
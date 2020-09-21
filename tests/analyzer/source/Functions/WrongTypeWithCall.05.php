<?php

class x {
    function foo(X|string $x) {} 
    function foo2(D|int $o) {} 
    
    function bar() {
        $this->foo('d');
        $this->foo(new X);

        $this->foo2('e');
        $this->foo2(new D);
    }
}

?>
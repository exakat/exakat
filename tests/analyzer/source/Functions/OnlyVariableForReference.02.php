<?php

// For testing purpose, this must be before the definition
A::foo($a, $b);
A::foo($a, $b[1]);
A::foo($a, $b->d);
A::foo(1, 2);
A::foo(1, C);
A::foo(1, array());
A::foo(1, x());

class A {
    static function foo($a, &$b) {
    
    }
}

function x() { }

?>
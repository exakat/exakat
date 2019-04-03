<?php

function foo(A $a, B $b, C $c) {
    $a = new A;
    clone $a;
    
    $b = new B;
    clone $b;

    $c = new C;
    clone $c;
}

class A {
    function __clone() {}
}

class B {
    private $b = null;
    
    function clone() {
        $this->b->yes();
    }
}

class C {
    private $b = 0;
    
    function clone() {
        $this->b++;
    }
}

?>
<?php

// variations : 
// interface au lieu de class
// properties pour les interfaces

class A {
    function a1() {}
    function a2() {}
}

class B {
    function b1() {}
}

class x {
    private $a, $b;

    function constructor(A $a, B $b) {
        $this->a = $a;
        $this->b = $b;
    }
    
    function foo( ) {
        $this->a->a1();
        $this->a->a2();
        $this->b->b1();
        $this->b->b2();
    }
}
?>
<?php

interface A { function a1(); }
interface B {

 function b1(); 
 function b2(); 
}


class x {
    private $a, $b;

    function constructor(A $a, B $b) {
        $this->a = $a;
        $this->b = $b;
    }
    
    function foo( ) {
        $this->a->a1();
        $this->a->a2;
        $this->b->b1();
        $this->b->b2();
    }
}
?>
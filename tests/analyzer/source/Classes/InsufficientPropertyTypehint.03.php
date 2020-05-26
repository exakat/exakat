<?php

// variations : 
// interface au lieu de class
// properties pour les interfaces

class A {
    public $a2;
    public $a3 = 3;
    
    function a1() {}
}

class B {
 function b1() {}
 function b2() {}
}


class x {
    private A $a;
    private B $b;
    private A $a2;
    private A $a3;

    function constructor(A $a, B $b, A $a2, A $a3) {
        $this->a = $a;
        $this->a2 = $a2;
        $this->a3 = $a3;
        $this->b = $b;
    }
    
    function foo( ) {
        $this->a->a1();
        $this->a->a2;

        $this->a2->a1();
        $this->a2->a3;

        $this->a3->a1();
        $this->a3->a4;

        $this->b->b1();
        $this->b->b2();
    }
}
?>
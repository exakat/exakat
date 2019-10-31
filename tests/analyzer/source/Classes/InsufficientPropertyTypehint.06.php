<?php

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
    private A $a, $a2, $a3;
    private B $b;

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
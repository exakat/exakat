<?php

trait t2 {
    function foo() {}
    function foo2() {}

    function foot1() {}
    function foot2() {}

    function taliased2(){ echo __METHOD__;}
}

trait t1 {
    function foo() {}
    function foo1() {}

    function foot1() {}
    function foot2() {}
    function taliased1(){ echo __METHOD__;}
}

class x {
    use t1, t2 {
        t1::foot1 insteadof t2;
        t2::foot2 insteadof t1;

        t1::taliased1 as alias1;
        t2::taliased2 as alias2;
    }

    function foo(){ echo __METHOD__;}

    function test(){ 
        $this->foo();
        $this->foo1();
        $this->foo2();

        $this->foot1();
        $this->foot2();

        $this->alias1();
        $this->alias2();
    }
}

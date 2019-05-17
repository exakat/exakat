<?php

trait t2 {
    function foo() { echo __METHOD__.PHP_EOL;}
    function foo2() { echo __METHOD__.PHP_EOL;}

    function foot1() { echo __METHOD__.PHP_EOL;}
    function foot2() { echo __METHOD__.PHP_EOL;}

    function taliased2(){ echo __METHOD__.PHP_EOL;}
}

trait t1 {
    function foo() { echo __METHOD__.PHP_EOL;}
    function foo1() { echo __METHOD__.PHP_EOL;}

    function foot1() { echo __METHOD__.PHP_EOL;}
    function foot2() { echo __METHOD__.PHP_EOL;}
    function taliased1(){ echo __METHOD__.PHP_EOL;}
}

class x {
    use t1, t2 {
        t1::foot1 insteadof t2;
        t2::foot2 insteadof t1;

        t1::taliased1 as alias1;
        t2::taliased2 as alias2;
    }

    function foo(){ echo __METHOD__.PHP_EOL;}

    function test(){ 
        self::foo();
        self::foo1();
        self::foo2();

        self::foot1();
        self::foot2();

        self::alias1();
        self::alias2();
    }
}

(new x)->test();
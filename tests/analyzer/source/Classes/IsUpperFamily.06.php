<?php

class AAAA {
    static function inAAAA() {}
}

class AAA extends AAAA {
    static function inAAA() {}
}

class AA extends AAA {
    static function inAA() {}
}

trait t {
    function inTrait() {} 
}

class A extends AA {
    use t; 
    
    static function inA() {}
    
    function foo() {
        a::inAAAA();
        a::inAAA();
        a::inAA();
        a::inA();
        a::inB();
        a::inTrait();

        a::nowhere();

        c::inC();
    }
}

class B extends A {
    static function inB() {}
}

class C {
    static function inC() {}
}

?>
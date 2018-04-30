<?php

class AA {
    static function inAA() {}
}

trait t {
    function inTrait() {} 
}

class A extends AA {
    use t; 
    
    static function inA() {}
    
    function foo() {
        a::{$inAA}();
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
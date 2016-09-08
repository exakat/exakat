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
        self::inAA();
        self::inA();
        self::inB();
        self::inTrait();

        self::nowhere();

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
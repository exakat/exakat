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
        static::inAA();
        static::inA();
        static::inB();
        static::inTrait();

        static::nowhere();

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
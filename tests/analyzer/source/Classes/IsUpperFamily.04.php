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
        parent::inAA();
        parent::inA();
        parent::inB();
        parent::inTrait();

        parent::nowhere();

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
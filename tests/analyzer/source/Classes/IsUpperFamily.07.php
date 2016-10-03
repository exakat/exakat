<?php

class AAAA {
    static public $inAAAA;
}

class AAA extends AAAA {
    static public $inAAA;
}

class AA extends AAA {
    static public $inAA;
}

trait t {
    static public $inTrait;
}

class A extends AA {
    use t; 
    
    static public $inA;
    
    function foo() {
        a::$inAAAA = 1;
        a::$inAAA = 1;
        a::$inAA = 1;
        a::$inA = 1;
        a::$inB = 1;
        a::$inTrait = 1;

        a::$nowhere = 1;

        c::$inC = 1;
    }
}

class B extends A {
    static public $inB;
}

class C {
    static public $inC;
}

?>
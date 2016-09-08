<?php

class AAAA {
    const inAAAA = 1;
}

class AAA extends AAAA {
    const inAAA = 1;
}

class AA extends AAA {
    const inAA = 1;
}

trait t { }

interface i {
    const inTrait = 1;
}

class A extends AA implements i {
    use t; 
    
    const inA = 1;
    
    function foo() {
        a::inAAAA;
        a::inAAA;
        a::inAA;
        a::inA;
        a::inB;
        a::inTrait;

        a::nowhere;

        c::$inC;
    }
}

class B extends A {
    const inB = 1;
}

class C {
    const inC = 1;
}

?>
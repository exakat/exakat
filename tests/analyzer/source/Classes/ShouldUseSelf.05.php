<?php

use A as E;

class A {
    const A = 1;
}

class B extends A { }
class C extends B { }

class D extends C {
    function foo() {
        echo A::A;
        echo B::A;
        echo A::G;
        echo G::G;
        echo self::B;
    }
}

class D2 extends C {
    function foo() {
        echo E::A;
        echo self::B;
    }
}


?>
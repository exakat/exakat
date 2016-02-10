<?php

namespace B {

use function A\A, B, A\F as G, A\H as I;
use function A\{D, E};

use A\{ B,
    function C,
    const D };

echo C(), A(), B(), I(), D(), E(), \A\A(), \strtolower(1), G();
//new B();
}

namespace A {
    function F() { print __METHOD__;}
    function E() { print __METHOD__;}
    function C() { print __METHOD__;}
    function A() { print __METHOD__;}
    // B is not defined. 
    
    const D = 3;
    
    class B {}
}
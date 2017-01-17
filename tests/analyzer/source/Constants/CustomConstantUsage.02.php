<?php


namespace A {
    const E = 'e';
    const C = 'c';
    const A = 'a';
    // B is not defined. 
}


namespace B {

use const A\A, B1;
use const A\{D1, E};

use A\{ B2,
    const C,
    function D2 };
//B, 

echo C, A, B, B1, B2, D, E, \A\A, \PHP_INT_MAX, D1, D2();
}

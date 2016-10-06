<?php

namespace A {
// This is visible 
use A;

class B {}

// This is hidden, because after the class
use C as D;
use C1 as D1, C2, C3 as D3, C4\C5\C5 as D4, C6\C6;

use some\namespace2\{ClassA, ClassB, ClassC as E};
use some\namespace2\{ClassA2, ClassB2, ClassC2 as F};
use function some\namespace2\{fn_a, fn_b, fn_c};
use const some\namespace2\{ConstA, ConstB, ConstC};

class EF extends D {
    use traitT; // This is a use for a trait

    function foo() {
        // This is a use for a closure
        return function ($a) use ($b) {};
    }
}

}


namespace b {
// this is visible 
use a;

class b {}

// this is hidden, because after the class
use c as d;
use c1 as d1, c2, c3 as d3, c4\c5\c5 as d4, c6\c6;

use some\namespace2\{classa, classb, classc as e};
use some\namespace2\{classa2, classb2, classc2 as f};
use function some\namespace2\{fn_a, fn_b, fn_c};
use const some\namespace2\{consta, constb, constc};

class ef extends d {
    use traitt; // this is a use for a trait

    function foo() {
        // this is a use for a closure
        return function ($a) use ($b) {};
    }
}

}

?>

<?php

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

?>

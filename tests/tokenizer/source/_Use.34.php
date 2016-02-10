<?php

namespace {
use N\A, N\B;
use function A\F, N\F2;
use const N\C, N\C2;
use N\D\{ D, function DF, const DC };
use N2\D2\{ D3, D2, function DF3, DF2, const DC3, DC2 };

use some\namespaces\{ClassA, ClassB, ClassC as C};
use function some\namespaces\{fn_a, fn_b, fn_c};
use const some\namespaces\{ConstA, ConstB, ConstC};

DF3();
//DF2();
new DF2();
}

namespace N2\D2 {
    function DF2() { print __FUNCTION__; }
    function DF3() { print __FUNCTION__; }
    
    class DF2 {
        function __construct() { print __METHOD__;}
    }
}
<?php

declare(strict_types = 1);

namespace B {

use const A\A, B;
use const A\B\C\{D, E};

use A\{ B,
    const C,
    function D };
//B, 

echo C1, A1, B1, D1, E1, \A1\A1, \PHP_INT_MAX;
}

?>
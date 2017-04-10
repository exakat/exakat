<?php

// Pre PHP 7 code
use some\namespace1\ClassA;
use some\namespace1\ClassB;
use some\namespace1\ClassC as C;

use function some\namespace2\fn_a;
use function some\namespace2\fn_b;
use function some\namespace2\fn_c;

use const some\namespace3\ConstA;
use const some\namespace3\ConstB;
use const some\namespace3\ConstC;

// PHP 7+ code
use some\namespace1\{ClassA1, ClassB1, ClassC1 as C1};
use function some\namespace2\{fn_a1, fn_b1, fn_c1};
use const some\namespace3\{ConstA1, ConstB1, ConstC1};

?>
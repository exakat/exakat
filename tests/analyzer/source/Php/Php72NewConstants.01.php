<?php

// Bad idea for PHP 7.2
const PHP_OS_FAMILY = 2;

class x {
    const PHP_FLOAT_DIG = 3; // OK
}

interface y {
    const PHP_FLOAT_EPSILON = 4; // OK
}

?>
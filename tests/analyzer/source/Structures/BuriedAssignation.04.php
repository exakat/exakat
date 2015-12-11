<?php

class x {
    private $property = 1, $property2 = 3;
    const A = 1, B =2;
    const C = 3;
}

static $D = 4;

if (false != ($b = strtolower($c))) {}

function x (Stdclass $y = null) {
    $a = 1 + 2 * ($b = intval($c));
}
?>
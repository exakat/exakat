<?php

function foo($a, $b) {
    $a = 1;
}

function bar($c, $d) {
    $x = func_get_args();
    $y = func_get_arg(1);
}

function foobar($e, $f) {
    $a->b = array_merge(func_get_args());
    return $a;
}


?>
<?php

class x {
    function foo($a, $b) {}
    function foo2($a, $b) {}
}

$x = new x();

$x->foo(1, 2);
$x->foo(2, 2);
$x->foo(3, 2);

$x->foo2(1, 2);
$x->foo2(2, 2);
$x->foo2(3, 3);

<?php

static $a;
static $b;

class x {
 function a($b, ...$c) {}
 function b2($b, Stdclass...$c2) {}
 function c ($d, $e): x  { $e; }
}
$a = new x;
$a->a();
$a->a(1);
$a->a(1,2);
$a->c();
$a->c(1);

$b = $a->c(1,2);

$b->a();
$b->a(3);
$b->a(3,4);
$b->c();
$b->c(3);
$b->c(3,4);

function foo(x $c) : x {
    $c->a();
    $c->a(5);
    $c->a(5,6);
    $c->c();
    $c->c(5);
    $c->c(5,6);
}

?>
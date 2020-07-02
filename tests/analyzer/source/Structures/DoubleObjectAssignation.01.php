<?php

$a = $b = new C;
$c = $d = clone $b;
$e = $f = $g = foo();
function foo() : C {}

$e1 = $f1 = $g1 = foo1();
function foo1() : ?C {}

$e2 = $f2 = $g2 = foo2();
function foo2() : ?int {}

?>
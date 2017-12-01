<?php


function foo($a, $b = 1, $c = 2) {}

foo(1,2);
foo(1,2);
foo(1,2);
foo(1,2);
foo(1,2, );

function foo2($a, $b = 1, $c = 2) {}

foo2(1,2);
foo2(1,2);
foo2(1,2);
foo2(1,2);
foo2(1,2, 3, );

function foo3($a, $b = 1, $c = 2, $d =4) {}

foo3(1,2);
foo3(1,2);
foo3(1,2);
foo3(1,2);
foo3(1,2, 3, );

function foo4($a, $b, $c) {}

foo4(1,2);
foo4(1,2);
foo4(1,2);
foo4(1,2);
foo4(1,2, );

?>
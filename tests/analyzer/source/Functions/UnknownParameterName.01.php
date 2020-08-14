<?php

function foo($a, $b, $c) {}
function goo($a2, $b2, $c2) {}

foo(a: 1, b: 2, c: 3);
foo( b: 2, c: 3, a: 1);
foo( b: 2, c: 3, d: 1);
foo( b: 2, c: 3, A: 1);
goo( b: 2, c: 3, A: 1);
?>

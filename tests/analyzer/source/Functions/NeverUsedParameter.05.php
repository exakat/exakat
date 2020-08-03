<?php
function foo($a, $b = 2, $c = 3) {}
foo(1,2);

function goo($a, $b = 2, $c = 5) {}
goo(1,2, 4);

function hoo($a, $b = 2, $c = 4) {}
hoo(b: 1, a: 2);

function ioo($a, $b = 2, $c = 6) {}
ioo(b: 1, a: 2);
ioo( a: 2, b: 1);

?>
<?php

function foo0($x, ...$b) {}
function foo1($x, $a, ...$b) {}
function foo2($x, $a, $c = 1, ...$b) {}

foo2();
foo2(0);
foo2(0, 1);
foo2(0, 1, 2);
foo2(0, 1, 2, 3);


?>
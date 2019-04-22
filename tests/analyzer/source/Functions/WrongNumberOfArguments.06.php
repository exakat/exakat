<?php

function foo0(...$b) {}
function foo1($a, ...$b) {}
function foo2($a, $c = 1, ...$b) {}

foo2();
foo2(0);
foo2(0, 1);
foo2(0, 1, 2);
foo2(0, 1, 2, 3);


?>
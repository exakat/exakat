<?php

function foo1($a, $b = 1) {}
foo1(1, 2);

function foo2($a, $b = 1) {}
foo2(1, 2);
foo2(1, 2);

function foo3($a, $b = 1) {}
foo3(1, 2);
foo3(1, 2);
foo3(1, 2);

function foo3b($a, $b = 1) {}
foo3b(1, 2);
foo3b(1, 2);
foo3b(1);

?>

<?php

function foo0($a, $b) {}
function foo1($a, $b) {}
function foo2($a, $b) {}
function foo3($a, $b) {}

// foo0 is never used

foo1(1, 2);


foo2(2, 2);
foo2(3, 2);

foo3(1, 2);
foo3(2, 2);
foo3(3, 2);

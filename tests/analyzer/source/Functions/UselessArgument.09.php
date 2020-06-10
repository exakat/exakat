<?php

function foo($a, $b) {}
function foo2($a, $b) {}

foo(1, null);
foo(2, null);
foo(3, null);

foo2(1, true);
foo2(2, 2);
foo2(3, 3);

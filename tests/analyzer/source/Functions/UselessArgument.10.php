<?php

function foo($a, $b) {}
function foo2($a, $b) {}

foo(1, true);
foo(2, true);
foo(3, true);

foo2(1, true);
foo2(2, 2);
foo2(3, false);

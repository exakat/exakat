<?php

function foo($a, $b) {}
function foo2($a, $b) {}

foo(1, "a");
foo(2, "a");
foo(3, "a");

foo2(1, "b");
foo2(2, "a");
foo2(3, "b");

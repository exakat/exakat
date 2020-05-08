<?php

const A = 2;
const B = 3;

function foo($a, $b) {}
function foo2($a, $b) {}

foo(1, 2);
foo(2, A);
foo(3, 2);

foo2(1, 2);
foo2(2, A);
foo2(3, B);

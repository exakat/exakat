<?php

function foo(int $a) {}

const B = 'c';
const D = 3;

foo('a');
foo(B);
foo(3);
foo(3 . '3');
foo((3 . '3'));
foo(@(3 . '3'));
foo($c = 3 . '3');
foo($d = 3);
foo($d = 3 + 5);

?>
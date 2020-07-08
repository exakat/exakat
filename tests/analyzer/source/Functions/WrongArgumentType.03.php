<?php

function foo(string|int $a) {}

const B = 'c';
const D = true;

foo('a');
foo(B);
foo(D);
foo(DE);
foo(3);
foo(3 . '3');
foo((3 . '3'));
foo(@(3 === '3'));
foo($c = 3 . '3');
foo($d = 3);

?>
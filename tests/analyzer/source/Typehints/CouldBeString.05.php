<?php

function foo(...$a) { bar($a);}
function foo2($a2) { bar($a2);}
function bar(string $b) {}

function fooA(...$aa) { bar($aa);}
function fooA2($aa2) { bar($aa2);}
function barA(string ...$b) {}

foo();
foo(1);
foo(a:2);
foo(3,4);

?>
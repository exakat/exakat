<?php

$a = fooArray();
$b = fooResource();
$c = fooIdentifier();
$d = fooNsname();
$e = fooInt();
$f = fooVoid();

$a++;
--$b;
$c | 3;
$d ** 2;
$e % 3;
3 & $f;
fooArray() + fooVoid();

function fooArray(): array { }
function fooResource(): resource { }
function fooIdentifier(): C { }
function fooNsname(): A\B { }
function fooInt(): int { }
function fooVoid() { }


?>
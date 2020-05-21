<?php

function foo() : array {}

$b = (array) foo();
$c = (string) foo();
$d = (int)  foo();

function goo() : ?array {}

$b = (array)  goo();
$c = (string) goo();
$d = (int)    goo();

$b = (array)  array_merge([], []);
$c = (string) array_merge([], []);
$d = (int)    array_merge([], []);

?>
<?php

function foo() : array {}

$b = (array) foo();
$c = (string) foo();
$d = (int)  foo();

$b = (array)  array_merge([], []);
$c = (string) array_merge([], []);
$d = (int)    array_merge([], []);

?>
<?php

// Not missing
$a = chdir('.');

$b = X();
$b = X;
class X{}

$b = Stdclass();
$b = Stdclass;

$c = Y();
function Y() {}

$d = Z();
// no definition

?>
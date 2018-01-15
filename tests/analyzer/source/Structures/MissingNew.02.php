<?php

// Not missing
$a = chdir('.');

$b = X();
$b = X;
class X{}

$b = Stdclass();
$b = \Stdclass();
$b = Stdclass;
$b = \Stdclass;

$c = Y();
$c = \Y();
function Y() {}

$d = Z();
$d = Z;
$d = \Z();
$d = \Z;
// no definition

?>
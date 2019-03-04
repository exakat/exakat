<?php

$a = 1;
$a2 = &$a;

$b = 1;
@$b = 1;

$c = 1;
$c = 1;

$d = new stdclass;
$d->b = 2;

$e = array();
$e[3] = 2;

$f = 3;

?>
<?php

include $a;
include $a[1];
include $a->m();

$b = 'a.php';
include $b;

$c = 'c.php';
include $c;

$d = 'c';
include $d.'.php';

include 'a.php';
include 'b.php';

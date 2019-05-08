<?php

$x1 = function ($a1, $b = 1) {};
$x1(1, 2);

$x2 = function ($a2, $b = 1) {};
$x2(1, 2);
$x2(1, 2);

$x3 = function ($a3, $b = 1) {};
$x3(1, 2);
$x3(1, 2);
$x3(1, 2);

$x3a = function ($a3a, $b = 1) {};
$x3a(1, 2);
$x3a(1, 2);
$x3a(1);

?>

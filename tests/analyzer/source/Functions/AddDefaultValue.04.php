<?php

$a = function () {};

$a = function ($x) { $x = 2;};
$a = function ($x = null) { $x = 2;};
$a = function ($x = 1) { $x = 2;};
$a = function ($x) { $x = new X;};
$a = function ($x) { $x = foo();};
$a = function ($x, $y, $z) { $x = $y;
                             $z = null;
                             $y = CONSTANTE;
                             };
$a = function ($x) { $x = CONSTANTE; };
$a = function ($x) { $x = 1 + 3; };


?>
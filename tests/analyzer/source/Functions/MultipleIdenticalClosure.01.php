<?php

$a = function ($x) { return $x * $x; };
$a = function ($a) { return $a * $a; };
$a = function ($b) { return $b * $b; };
$a = function ($y) { ++$y; return $y * $y; };
$a = function ($z) { ++$x; return $z * $z; };
$a = function ($c) { $c++; return $c * $c; };

?>
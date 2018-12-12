<?php

$a = function ($x) { return $x * $x; };
function foo($a) { return $a * $a; }

$a = function ($y) { ++$y; return $y * $y; };

?>
<?php

$a = function ($y) use ($u) { return $u; };
$a = function ($y, $z) use ($u) { return $u; };

$a = function ($y) use ($U) { return $y; };
$a = function ($y) use ($U, $V) { return $y; };
$a = function ($y) use ($U, $V, $W) { return $y; };
$a = function ($y) use ($U, $V, $W, $a) { return $y + $a; };

?>
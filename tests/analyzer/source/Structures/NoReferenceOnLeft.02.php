<?php

$b = true;
$a = $b + $c;

// can't compile with this one
// $a = $b + &$c;

$a = &$b . $c;
$a = &$b * $c;
$a = &$b > $c;
$a = &$b >> $c;
$a = &$b instanceof Stdclass;
$a = &$b ** 3;

$a = &$b && true;

$a = &$c;

?>

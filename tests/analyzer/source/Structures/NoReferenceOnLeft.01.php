<?php

$a = $b + $c;

// can't compile with this one
// $a = $b + &$c;

$a = &$b + $c;
$a = &$b->d + $c;
$a = &$b[1]->d + $c;

?>
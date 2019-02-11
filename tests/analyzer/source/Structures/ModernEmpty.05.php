<?php

$a = $b[1][2];
$c = $d + 3; 
$e = $f->g;
$h = I::$j;
$k = I::L;
$a2 = A::B($b[1])[2];
$a3 = A::B($b[3]);

if (empty($a)) {}
if (empty($a2)) {}
if (empty($a3)) {}
if (empty($b)) {} // not assigned with expression

?>
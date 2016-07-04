<?php


$a == 1 ? $b : $b;

$c == 1 ? $b[1] : $b[3];
$a == 2 ? $b[2] : $b[2];

$a == 3 ? $a->method() : $a->method();

if ($a == 1)  echo $b;  else  echo $b;

if ($a == 2) $b = strtolower($b[2]); else $b = strtolower($b[2]);

if ($a == 3) : $a->method(); else : $a->method(); endif;

if ($a == 4) {$b = strtolower($b[2]);} else {$b = strtolower($b[2]);}

if ($c == 1) echo $b[1]; else echo $b[3];
// Long sequence
if ($c == 2) {$b = strtolower($b[2]); $a++;} else {$b = strtolower($b[2]); $a++;}


?>
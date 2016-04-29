<?php


if ($a == 1) : echo $b; echo $c; else :  echo $b; echo $d; endif;
if ($a == 2) : echo $b; echo $d; else :  echo $b; echo $d; endif;

// Long sequence
if ($c == 2) {$b = strtolower($b[2]); $a++;} else {$b = strtolower($b[2]); $a++;}
if ($c == 3) {$b = strtolower($b[3]); $d++;} else {$b = strtolower($b[3]); $a++;}
if ($c == 4) {$b = strtolower($b[3]); $d++; if ($a) {} else {}} else {$b = strtolower($b[3]); $a++;}


?>
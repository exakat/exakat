<?php

$a or $b = 1;

$c == 2 || $d == 2;
$a = 1 && $b;   // Yes, as it is $a1 = (1 && $b);
$a2 = 1 and $b; // Can't find, it's actually ($a2 = 1) && $b
$a = 1 & $b;   // Yes, as it is $a1 = (1 && $b);

?>
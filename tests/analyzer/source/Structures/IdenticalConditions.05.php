<?php

$a = 7;
$c = 1;
// all distinct
print $a && $b && $c && $d;

// 3 level
print $a3 && $b && $c && $a3;

// 4 level
print $a4 && $b && $c && $d && $a4;

// 5 level
print $a5 && $b && $c && $d && $e && $a5;

// 5 level in a larger expression
print $e && $a5a && $b && $c && $d && $e && $a5a;

// 6 level (won't find but should some day)
print $a6 && $b && $c && $d && $f && $g && $a6;


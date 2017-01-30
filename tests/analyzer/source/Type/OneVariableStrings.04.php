<?php

$a = 0;
$b = "$a"; // This is a one-variable string

// Better way to write the above
$b = (string) $a;

// Alternatives : 
$b2 = "$a[1]"; // This is a one-variable string
$b3 = "$a->b"; // This is a one-variable string
$c = "d";
$d = "D";
$b4 = "{$$c}";

$a = new class { function foo() { echo 'inside A'; }
static function foo2() { echo 'static a'; }};
$b5 = "{$a->foo()}";
echo $b5 = "{$a::foo2()}";


$c1 = "{foo()}";
$c1 = "{$a->foo()}";


function foo() { return __FUNCTION__; }
?>

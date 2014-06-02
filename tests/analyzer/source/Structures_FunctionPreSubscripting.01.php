<?php

function f() { return array(123); }

$a = f();
$a = $a[0];
print $a;

$c = f();
$d = $c[0];

$e = f();
$g = $h[0];

?>
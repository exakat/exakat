<?php

$x = strtolower($x);
$x2->a = strtolower(strtolower($x2->a));
$x3::$b = rand(strtolower($x3::$b), strtoupper($x3::$b));
$x4::$b = rand($x[3],2);
$x5::$b = array_reverse($x5::$b[3]);
$x6 = array_fill($x6->x, 3, 4);
$z7::$d = A . $z7::$d . foo($b);
?>
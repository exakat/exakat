<?php

include ('def.php');

$a = new foo();

$a->foo('a');
$a->foo($x);
$a->foo($_GET);
$a->foo($y[1]);
$a->foo($y->a);
$a->foo($y::$C);
$a->foo($y::D);

?>
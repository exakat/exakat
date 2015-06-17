<?php

$x = new \Finfo();

$mf = new MessageFormatter('en_US', '{this was made intentionally incorrect}');

$y = new \Stdclass;

$z = new PDO;


$b = new NumberFormatter();


// Those are OK
$a = IntlDateFormatter();
$c = new \B\NumberFormatter;
$d = new \C\NumberFormatter;
$y = new \Stdclass;

$e = new $f();

?>
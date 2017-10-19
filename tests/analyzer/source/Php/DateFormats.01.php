<?php

$a = time();

$b = date('r', $a);

$date = new DateTime('2000-01-01');
echo $date->format('Y-m-d H:i:s');

// Wrong. Not a date.
echo date::format('Y-m-d H:i:s');

$date = date_create('2000-01-01');
echo date_format($date, 'Y-m-d H:i:s');

// Wrong, not a date
echo $x->date_format($date, 'Y-m-d H:i:s2');

?>
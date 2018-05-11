<?php

$a = "{$b}c" . PHP_EOL;
$a = "{$b}c" . $a->b->c;

$a = "{$b}c" . ($a ? $b : $c);
$a = "{$b}c" . CONSTANT;
$a = "{$b}c" . A::CONSTANT;
$a = "{$b}c" . A::$member;
$a = "{$b}c" . $object->$member;
$a = "{$b}c" . $array[$member];
$a = "{$b}c" . number_format($a, 2);
$a = "{$b}c" . $a->{$b.'c'};
$a = "{$b}c" . $a['b']['c'];

?>
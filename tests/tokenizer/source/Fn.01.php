<?php

$a = fn($x) => $x * 4; ;

echo $a(3);
echo $a(4);


$b = function ($y) {};
$b(3);

$versionA = fn($x) => $x + $y;


fn (array $x) => $x;
fn (): int => 42;


static fn($x) => static::get($x);
fn($x) => static::get($x);

$extended = fn($c) => $callable($factory($c), $c);

$a->existingSchemaPaths = array_filter($paths, fn($v) => in_array($v, $names));

?>
<?php

$h = 100;

$a = $b[$c] + $d - $e->f[$g] ?? $h;
$a = $b[$c] + $d * $e->f[$g] ?? $h;
$a = $b[$c] + $d == $e->f[$g] ?? $h;
$b[$c] + $d . $e->f[$g] ?? $h;
$a = $b[$c] + $d ** $e->f[$g] ?? $h;
$a = $b[$c] + $d instanceof $e->f[$g] ?? $h;
$a = $b[$c] + $d & $e->f[$g] ?? $h;

var_dump($a);
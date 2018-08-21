<?php

$a = array(1, 2, 3, 4, 5);
$b = array_map(function ($n)
{
    return($n * $n * $n);
}, $a);
$b = array_map(function (&$n)
{
    $n = ($n * $n * $n);
}, $a);
$b = array_map(function ($n) use (&$b)
{
    $a = ($n * $n * $n);
}, $b);

$b = array_map(function ($n) use ($b)
{
    $a = ($n * $n * $n);
}, $b);

?>
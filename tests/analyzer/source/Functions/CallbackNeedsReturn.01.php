<?php

function cube($n)
{
    return($n * $n * $n);
}

function cube2($n)
{
    ($n * $n * $n);
}

$a = array(1, 2, 3, 4, 5);
$b = array_map("cube", $a);
$b = array_map("cube2", $a);

$b = array_map(function ($n2) {    ($n * $n * $n);}, $a);
$b = array_map(function ($n) {    return ($n * $n * $n);}, $a);

?>
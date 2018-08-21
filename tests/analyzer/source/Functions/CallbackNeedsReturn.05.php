<?php

function cube($n)
{
    return($n * $n * $n);
}

function cube2(&$n)
{
    $n = ($n * $n * $n);
}

function cube3($n)
{
    $n = ($n * $n * $n);
}

$a = array(1, 2, 3, 4, 5);
$b = array_map("cube", $a);
$b = array_map("cube2", $a);
$b = array_map("cube3", $a);

?>
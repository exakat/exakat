<?php

function cube($n)
{
    return($n * $n * $n);
}

function cube2($n)
{
    ($n * $n * $n);
}

$b = array_map("cube", $a);
$b = register_shutdown_function("cube", $a);

$b = array_map("cube2", $a);
$b = register_shutdown_function("cube2", $a);

?>
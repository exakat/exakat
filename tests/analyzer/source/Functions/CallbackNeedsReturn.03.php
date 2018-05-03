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
$b = array_filter($a, "cube");
$b = array_filter($a, "cube2");

$b = array_filter($a, function ($n2) {    ($n * $n * $n);});
$b = array_filter($a, function ($n) {    return ($n * $n * $n);});

class x {
    static function cube($n)
    {
        return($n * $n * $n);
    }
    
    static function cube2($n)
    {
        ($n * $n * $n);
    }
}
$a = array(1, 2, 3, 4, 5);
$b = array_filter($a, array('x', 'cube'));
$b = array_filter($a, array('x', 'cube2'));

?>
<?php

class x {
    static function cube($n)
    {
        return($n * $n * $n);
    }
    
    static function cube2($n)
    {
        ($n * $n * $n);
    }

    static function cube3(&$n)
    {
        $n = ($n * $n * $n);
    }

    static function cube4(&$n)
    {
        return ($n * $n * $n);
    }
}
$a = array(1, 2, 3, 4, 5);
$b = array_filter($a, array('x', 'cube'));
$b = array_filter($a, array('x', 'cube2'));
$b = array_filter($a, array('x', 'cube3'));
$b = array_filter($a, array('x', 'cube4'));

?>
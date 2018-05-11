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
}
$a = array(1, 2, 3, 4, 5);
$b = array_map(array('x', 'cube'), $a);
$b = array_map(array('x', 'cube2'), $a);

?>
<?php

$array = [null, true];
var_dump($array[0][0][1] === $array[1][2][3]);

$a = null;
echo $a[3];

$b = true;
echo $b[3];

function foo() {
    static $x = true, $X = 3;
    global $y, $Y;
    
    $y = true;
    $Y = [];
    
    echo $x[1] + $y[3];
    echo $X[1] + $Y[3];
}

?>
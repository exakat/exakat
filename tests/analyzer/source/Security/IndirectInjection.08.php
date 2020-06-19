<?php

function foo($d) {
    global $C;
    static $s;

    $a = $_GET['x'];
    bar($a);
    $b = $a;
    $C = $a;
    $d = $a;
    $s = $a;
    
    echo $b;
    echo $d;
    echo $s;
    
    return $a;
}

function bar($c) {
    echo $c;
}

echo foo();

global $C;
echo $C;

?>
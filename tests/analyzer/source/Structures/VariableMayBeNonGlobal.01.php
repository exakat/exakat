<?php 

function g1() : Generator {
    $a = 1;
    $s = 5;
    global $a;
    global $b;
    static $s;
    static $t;

    $a = 3;
    $b = 4;
    $s = 55;
    $t = 66;
}

?>
<?php

function foo($a, $a2, $b, $c, $d) {
    $a[]  = 1;
    echo $a2[2];
    bar($b);
    chr($c);
    array_keys($d);
}

function bar(array $c) {}

?>
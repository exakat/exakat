<?php 

function foo($a, &$b, &$c) {
    $a++;
    $b += $c;
}

function foo2($a, $b, $c) {
    $a++;
    $b += $c;
}

function foo3($a, $b, &$c) {
    $a++;
    $b += $c;
}

function foo4($a, &$b, $c) {
    $a++;
    $b += $c;
}

?>
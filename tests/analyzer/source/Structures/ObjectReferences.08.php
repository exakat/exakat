<?php 

$a = new stdClass;
$a->b = 3;
function foo4( $a) {
    $a->c = 3;
}
foo4($a);

function foo3(&$a) {
    $a = clone $b;
    $a->b = 3;
}

function foo2(&$a) {
    $a->b = 3;
}

function foo($a) {
    $a = new stdClass;
    $a->b = 3;
}

$a = 1;

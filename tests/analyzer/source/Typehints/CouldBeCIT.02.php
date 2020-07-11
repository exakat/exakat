<?php


function byTypedArg(A $a1) {
    return $a1;
}
/*
function byDefault($a2 = true) {
    return $a2;
}
*/
define('B', new X);
function byAtoms() {
    return new X;
}

function byAtoms2() {
    return B;
}

function notByAtoms4() {
    return C;
}
/*
function byPHP() {
    return is_array();
}

function byPHP2() {
    $a = is_array();
    return $a;
}
*/
function byRelay() {
    return bar();
}

function bar() : G {}

?>
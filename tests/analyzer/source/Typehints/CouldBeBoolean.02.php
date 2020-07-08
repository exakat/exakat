<?php


function byTypedArg(bool $a1) {
    return $a1;
}

function byDefault($a2 = true) {
    return $a2;
}

const B = true;
function byAtoms() {
    return false;
}

function byAtoms2() {
    return B;
}

function byAtoms3() {
    return rand(1, 2) == rand(3, 5);
}

function notByAtoms4() {
    return C;
}

function byPHP() {
    return is_array();
}

function byPHP2() {
    $a = is_array();
    return $a;
}

function byRelay() {
    return bar();
}

function bar() : bool {}

?>
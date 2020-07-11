<?php


function byTypedArg(int $a1) {
    return $a1;
}

function byDefault($a2 = 3) {
    return $a2;
}

const B = 4;
function byAtoms() {
    return 3;
}

function byAtoms2() {
    return B;
}

function byAtoms3() {
    return 3 ** 4;
}

function notByAtoms4() {
    return C;
}

function byPHP() {
    return pow(B, 3);
}

function byPHP2() {
    $a = pow(B, 3);
    return $a;
}

function byRelay() {
    return bar();
}

function bar() : int {}

?>
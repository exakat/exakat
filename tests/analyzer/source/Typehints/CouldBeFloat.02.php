<?php


function byTypedArg(float $a1) {
    return $a1;
}

function byDefault($a2 = 3.3) {
    return $a2;
}

const B = 4.1;
function byAtoms() {
    return 3.8;
}

function byAtoms2() {
    return B;
}

function byAtoms3() {
    return 3.3 ** 4.4;
}

function notByAtoms4() {
    return C;
}

function byPHP() {
    return pow(B, 0.3);
}

function byPHP2() {
    $a = pow(B, 3.1);
    return $a;
}

function byRelay() {
    return bar();
}

function bar() : float {}

?>
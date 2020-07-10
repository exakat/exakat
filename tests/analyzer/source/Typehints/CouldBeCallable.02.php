<?php


function byTypedArg(callable $a1) {
    return $a1;
}

function byDefault($a2) {
    $a2 = function () {};
    return $a2;
}

function byDefault2($a22) {
    $a22 = fn () => 1;
    return $a22;
}

define('B', fn () => 1);
function byAtoms() {
    return function () {};
}

function byAtoms2() {
    return B;
}

function notByAtoms4() {
    return C;
}

function byRelay() {
    return bar();
}

function bar() : callable {}

?>
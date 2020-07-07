<?php


function byTypedArg(array $a1) {
    return $a1;
}

function byDefault($a2 = array()) {
    return $a2;
}

const B = array(1);
function byAtoms() {
    return array(1);
}

function byAtoms2() {
    return B;
}

function byAtoms3() {
    return [3];
}

function notByAtoms4() {
    return C;
}

function byPHP() {
    return array_diff();
}

function byPHP2() {
    $a = array_diff();
    return $a;
}

function byRelay() {
    return bar();
}

function bar() : array {}

?>
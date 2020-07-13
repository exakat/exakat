<?php


function byTypedArg(iterable $a1) {
    return $a1;
}

function byDefault($a2 = array()) {
    return $a2;
}

const B = array();
function byAtoms() {
    return array();
}

function byAtoms2() {
    return B;
}

function byAtoms3() {
    return array();
}

function notByAtoms4() {
    return C;
}

function byRelay() {
    return bar();
}

function bar() : iterable {}

?>
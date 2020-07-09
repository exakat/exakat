<?php


function byTypedArg(string $a1) {
    return $a1;
}

function byDefault($a2 = 'a') {
    return $a2;
}

const B = 'c';
function byAtoms() {
    return 'd';
}

function byAtoms2() {
    return B;
}

function byAtoms3() {
    return 'a'.'b';
}

function notByAtoms4() {
    return C;
}

function byPHP() {
    return strtolower($a);
}

function byPHP2() {
    $a = strtolower($a);
    return $a;
}

function byRelay() {
    return bar();
}

function bar() : string {}

?>
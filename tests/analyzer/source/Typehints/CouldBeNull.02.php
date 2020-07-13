<?php


function byTypedArg(?int $a1) {
    return $a1;
}

function byDefault($a2 = null) {
    return $a2;
}

const B = null;
function byAtoms() {
    return null;
}

function byAtoms2() {
    return B;
}

function notByAtoms4() {
    return C;
}

function byPHP() {
    return shell_exec('');
}

function byPHP2() {
    $a = shell_exec('');
    return $a;
}

function byRelay() {
    return bar();
}

function bar() : ?int {}

?>
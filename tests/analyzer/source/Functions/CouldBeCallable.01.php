<?php

function foo1($callable) {
    $callable();
}

function foo2(callable $AlreadyCallable) {
    $AlreadyCallable();
}

function foo3(callable $AlreadyCallable2) {
    return $AlreadyCallable;
}

function foo4($dunno) {
    return $dunno;
}


?>
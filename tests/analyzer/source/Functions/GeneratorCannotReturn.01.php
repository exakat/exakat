<?php

function foo() {
    yield 1;
    return 1;
}

function foo1() {
    yield from foo();
    return 1;
}

function bar() {
    return 1;
}

function barbar() {
    yield 1;
}

function barbarbar() {
    yield from foo1();
}

?>
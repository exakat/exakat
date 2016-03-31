<?php

function yielding() {
    yield 3;
}

function yielding2() {
    yield 3;
    if (rand(0,3) > 2) {
        yield 3;
    }
}

function yieldingfrom() {
    yield from yielding();
}

?>
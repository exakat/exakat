<?php

function generator() {
    yield from generator2();
    
    return 3;
}

function generator2() {
    yield 1;
    yield 2;
}

function notAGenerator() {
    return;
}

?>
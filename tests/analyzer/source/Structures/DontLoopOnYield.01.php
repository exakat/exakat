<?php

function generator() {
    for($i = 0; $i < 10; ++$i) {
        yield $i;
    }
}

function delegatingGenerator() {
    yield from generator();
}

// Too much code here
function generator2() {
    foreach(generator() as $g) {
        yield $g;
    }
}

function generator3() {
    foreach(generator3() as $g3) {
        yield from $g3;
    }
}

?>
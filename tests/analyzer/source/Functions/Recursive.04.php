<?php

function foo() {
    foo();
}

function generator3() {
    yield 1;
    yield from generator3();
    yield 4;
    
    return 3;
}

function generator() {
    yield 1;
    yield from generator2();
    yield 4;
    
    return 3;
}

function generator2() {
    yield 2;
    yield 3;
    yield from generator();
}

foreach(generator() as $i) {
    print "$i)\n";
}


?>
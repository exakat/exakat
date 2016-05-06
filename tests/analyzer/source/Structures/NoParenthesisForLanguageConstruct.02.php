<?php

function x() {
    yield (1);
    yield from (y());
}

function y() {
    yield 2;
}

foreach(x() as $y) {
    print "$y\n";
}

function from() {return 3;}

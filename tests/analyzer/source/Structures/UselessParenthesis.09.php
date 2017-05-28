<?php

function foo() {
    $x = 2;
    yield 1;
    yield ($x);
    yield from foo2();
    yield from (foo3());
}

function foo2() {
    yield 3;
}

function foo3() {
    yield 4;
}

foreach(foo() as $f) {
    print "$f\n";
}
?>
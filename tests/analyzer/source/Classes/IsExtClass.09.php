<?php

function foo() : \Generator {
    Yield 33;
}

function foo2(stdclass $x) {
    Yield 33;
}

// not smart, but good for test
const Exception = 1;

?>
<?php

foo();
foo(1);
foo(1 + 2);
foo($a);
foo(x::$y);
foo(C);
foo(X::class);
foo(bar());
foo(bar_with_ref());

function bar() {
    $a = 1;
    return $a;
}

function &bar_with_ref() {
    $a = 1;
    return $a;
}

function foo(&$r) { }
?>
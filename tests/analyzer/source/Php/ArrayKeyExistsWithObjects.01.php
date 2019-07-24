<?php

function foo(A $a, string $b) {
    array_key_exists('a', $a);
    array_key_exists('b', $b);
}

function foo3() : A      { return new A; }
function foo4() : string { return 'sr'; }

function foo2() {
    $a2 = foo3();
    $b2 = foo4();
    array_key_exists('a', $a2);
    array_key_exists('b', $b2);
}

function foo5() {
    array_key_exists('a', $a5);
    array_key_exists('b', $b5);
    array_key_exists('c', $c5);

    $a5->b = 3;
    $c5->b();
    $b5 = array(3);
}

?>
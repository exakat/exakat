<?php

function foo() {
    ++$x;
    unset($a);
    unset($b);
    unset($c);
    unset($d);
}

function foo2() {
    ++$x;
    unset($a2);
    unset($b2);
}

function foo4() {
    ++$x;
    unset($a4);
    $c;
    unset($b4);
}


?>
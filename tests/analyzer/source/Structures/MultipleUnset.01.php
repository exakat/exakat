<?php

function foo() {
    unset($a);
    unset($b);
    unset($c);
    unset($d);
}

function foo2() {
    unset($a2);
    unset($b2);
}

function foo4() {
    unset($a4);
    $c;
    unset($b4);
}


?>
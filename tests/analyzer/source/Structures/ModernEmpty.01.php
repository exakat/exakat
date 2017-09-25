<?php

function foo() {
    // PHP 5.5+ empty() usage
    $d = strtolower($b0 . $c0);
    if (empty(strtolower($b0 . $c0))) {
        doSomethingWithoutA();
    }
}

function foo2() {
    // Compatible empty() usage
    $a = strtolower($b . $c);
    if (empty($a)) {
        doSomethingWithoutA();
    }
}

function foo3() {
    // $a is reused
    $a2 = strtolower($b . $c);
    if (empty($a2)) {
        doSomethingWithoutA();
    } else {
        echo $a2;
    }
}

?>
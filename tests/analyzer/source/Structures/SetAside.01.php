<?php

function foo() {
    // strict minimum
    $b = $a; 
    $a = 3; 
    $a = $b;
}

function foo2() {
    // $a <-> $b is broken
    $b = $a; 
    $a = 3; 
    $a = $c;
}

function foo3() {
    // $a is not reassigned
    $b = $a; 
    $a = $b;
}

?>
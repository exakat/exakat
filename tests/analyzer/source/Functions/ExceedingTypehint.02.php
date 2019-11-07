<?php

interface i {
    function I1();
    function I2();
}

interface j {
    function J1();
    function J2();
}

function foo(i $a, j $b) {
    $a->i1();
    $a->i2();
    
    $b->j1();
}

?>
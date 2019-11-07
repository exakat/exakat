<?php

interface i {
    function i1();
    function i2();
}

interface j {
    function j1();
    function j2();
}

function foo(i $a, j $b) {
    $a->i1();
    $a->i2();
    
    $b->j1();
}

?>
<?php

function a() {

    // Not a closure, a function definition
    function ($cf) {
        $cf = 2;
        $cf++;
        $df = 2;
        $ff = 3;
        $ff++;
        $d = 2;
        $g = 3;
    };
    
    $d = 3;
    $c = 1;
    $e = 3;
    $b++;
}
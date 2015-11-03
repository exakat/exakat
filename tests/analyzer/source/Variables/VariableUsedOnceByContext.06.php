<?php

function a() {

    $b = function ($c) {
        $c = 2;
        $c++;
        $d = 2;
        $f = 3;
        $f++;
    };
    
    $d = 3;
    $c = 1;
    $e = 3;
    $b++;
}
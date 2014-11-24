<?php

function multipleReturn() {
    if ($a) {
        return 1;
    } else {
        return 2;
    }
}

function singleReturn() {
    $r = 1;

    if ($a) {
        $r = 1;
    } else {
        $r = 2;
    }
    
    return $r;
}

function noReturn() {
    $r = 1;

    if ($a) {
        $r = 1;
    } else {
        $r = 2;
    }
}

?>
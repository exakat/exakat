<?php

function foo0() {
    $a = 1;
    $a = 2;
}

function foo0_0() {
}

// average 0.66
function foo0_66() {
    if ($a == 2) {
        $a = 1;
    } else {
        $a = 2;
    }
}

// average 1
function foo1() {
    if ($a == 2) {
        if ($a == 2) {
            $a = 1;
        }
        $a = 1;
    } else {
        $a = 2;
    }
}

// average 2
function foo1_25() {
    if ($a == 2) {
        if ($a == 2) {
                $a = 1;
        } else {
               $a = 2;
        }
     }
}

?>
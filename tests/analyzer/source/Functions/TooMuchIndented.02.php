<?php

function foo0() {
    $a = 1;
    $a = 2;
}

// average 0.66
function foo0_66() {
    foreach ($a as $b) {
        $a + 1;
        $b + 1;
    }
}

// average 0.66
function foo1() {
    foreach ($a as $b) {
        $a + 1;
        $b + 1;
        if ($a == 2) {
            $a = 1;
        }
    }
}

// average 0.66
function foo1_25() {
    foreach ($a as $b) {
        $a + 1;
        $b + 1;
        if ($a == 2) {
            $a = 1;
        } else {
               $a = 2;
        }
    }
}

?>
<?php

function foo0() {
    $a = 1;
    $a = 2;
}

// average 0.66
function foo0_66() {
    for ($i = 0; $i < 10; ++$i) {
        $a + 1;
        $b + 1;
    }
}

// average 0.66
function foo1() {
    for ($i = 0; $i < 10; ++$i) {
        $a + 1;
        $b + 1;
        if ($a == 2) {
            $a = 1;
        }
    }
}

// average 0.66
function foo1_25() {
    for ($i = 0; $i < 10; ++$i) {
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
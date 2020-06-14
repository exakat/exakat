<?php

function foo0() {
    $a = 1;
    $a = 2;
}

// average 0.66
function foo0_66() {
    do {
        $a + 1;
        $b + 1;
    } while ($a == $b);
}

// average 0.66
function foo1() {
    do {
        $a + 1;
        $b + 1;
        if ($a == 2) {
            $a = 1;
        }
    } while  ($a == $b);
}

// average 0.66
function foo1_25() {
    do {
        $a + 1;
        $b + 1;
        if ($a == 2) {
            $a = 1;
        } else {
               $a = 2;
        }
    } while ($a == $b);
}

?>
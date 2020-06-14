<?php

function foo0() {
    $a = 1;
    $a = 2;
}

// average 0.66
function foo0_66() {
    try {
        $a + 1;
    } catch (Exception $e) {
        $b + 1;
    }
}

// average 0.66
function foo1() {
    try {
        $a + 1;
        if ($a == 2) {
            $a = 1;
        }
    } catch (Exception $e) {
        $b + 1;
    }
}

// average 0.66
function foo1_25() {
    try {
        if ($a == 2) {
            $a = 1;
        } else {
               $a = 2;
        }
    } catch (Exception $e) {
        $a + 1;
    } finally {
        $b + 1;
    }
}

?>
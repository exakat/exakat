<?php

    // Closure in then
    if ($constant === 1) {
        $cb = function ($r) {
            return $r;
        };
    } else {
        $a++;
    }

    // Closure in else
    if ($constant === 2) {
        $a++;
    } else {
        $cb = function ($r) {
            return $r;
        };
    }

    // No Closure 
    if ($constant === 3) {
        return $r;
    } else {
        $a++;
    }

?>
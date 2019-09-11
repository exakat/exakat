<?php

function foo(array $a) {
    if ($a === 3) {
        // deacode
    } elseif ($a === 'string') {
        // also dead
    } else {
        // OK
    }
    
    if ($a === [3]) {
        // not dead
    }
}
?>
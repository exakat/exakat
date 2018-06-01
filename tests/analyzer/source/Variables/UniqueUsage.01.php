<?php

function foo($d) {
    $a = 1;     // $a is used twice
    $b = $a + 2;  // $b is used once
    $c = $a + $b + $d; // $c is also used once
    // $d is an argument, so it's OK.
    
    return $c;
}

?>
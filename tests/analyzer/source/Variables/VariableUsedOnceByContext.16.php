<?php

function foo() {
    $a = 1;
    $b = 2;
    $e = 3;
    
    $c = compact('b', 'd');
    
    return $c + $a;
}
?>
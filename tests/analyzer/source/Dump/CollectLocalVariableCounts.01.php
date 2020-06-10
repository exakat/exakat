<?php

// 2 local variables
function foo($arg) {
    // This is a local variable
    $x = rand(1, 2);
    
    return $x + $arg + $w;
}

// 1 local variables
function fooGlobal($arg) {
    global $w;

    // This is a local variable
    $x = rand(1, 2);
    
    return $x + $arg + $w;
}

// 3 local variables
function fooStatic($arg) {
    static $s1, $s2;

    // This is a local variable
    $x = rand(1, 2);
    
    return $x + $arg + $s1 + $s2;
}

class x {
    // 0 variable
    function fooGlobal($arg) {
        return $this->a + $arg;
    }
}
?>
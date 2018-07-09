<?php

function foo1() {
    return $a->a = 2;
}

function foo2() {
    return $var = 2;
}

function foo3() {
    return $array[2] = 2;
}

function foo4() {
    return $_GET = 2;
}

function foo5() {
    return A::$b = 3;
}

function foo6() {
    return A::$b[3] = 3;
}

function foo7($a) {
    return $a = 3;
}

function foo8(&$a2) {
    return $a2 = 3;
}

function foo9() {
    static $a3;
    
    return $a3 = 3;
}

function foo10() {
    global $a4;
    
    return $a4 = 3;
}

?>
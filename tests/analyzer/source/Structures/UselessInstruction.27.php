<?php

function foo() {
    static $static;
    
    return $static++;
}

function foo2() {
    global $global;
    
    return $global++;
}

function foo3() {
    return $normal++;
}

?>
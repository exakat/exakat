<?php

$globalVariable = 1;
$globalButLocal = 1;

function foo() {
    global $globalVariable;
    
    $globalVariable = 2;

    $globalButLocal = 1;
    
    $localOnly = 3;
}

function variablesButNoGLobal() {
    $a = 1;
}

function globalsButNoVariable() {
    global $a;
}

?>
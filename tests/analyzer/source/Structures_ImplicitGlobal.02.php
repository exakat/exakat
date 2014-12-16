<?php

global $explicitGlobalVar; // explicit global variable
$implicitGlobalVar; // naturally global var_dump

function x () {
    global $explicitGlobalVar, $implicitGlobalVar;
    global $argv, $argc;
    
}

function x2 () {
    global $explicitGlobalVar, $implicitGlobalVar;
}

?>
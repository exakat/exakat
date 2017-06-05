<?php

$global = 1;

function foo() {
    global $global;
    global $uselessGlobal;
    
    $global += 1;
    $uselessGlobal += 1;
    $foo = 1;
}
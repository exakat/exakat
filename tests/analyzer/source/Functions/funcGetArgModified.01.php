<?php

function funcGetArgModified($a) {
    $a++;
    $c = func_get_arg(0);
}

function funcGetArgModified1($a, $b) {
    $b++;
    $c = func_get_arg(1);
}

function funcGetArgModified2($a, $b, $c) {
    $c++;
    $e = func_get_arg(2);
}

function funcGetArgNotModified($a, $b, $c) {
    $e = $c + 2;
    $d = func_get_arg(2);
}

function funcGetArgNotUsedAsVariable($a, $b, $c) {
    $d = func_get_arg(2);
}

function funcGetArgNotInArg($a, $b) {
    $d = func_get_arg(2);
}

function noFuncGetArg($a, $b) {
    $d = func_get_arg(2);
}

?>
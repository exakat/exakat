<?php

function foo3($a = 2, $fields) {
    $fields = [$fields];
}

function foo4($a = 2, $fields) {
    $fields = [3];
}

function foo5($a = 2, $fields) {
    $fields = [E_ALL];
}

function foo6($a = 2, $fields) {
    $fields = [];
}

function foo(&$x) { $x = 2;}
function foo2(...$x) { $x = 2;}

?>
<?php

foo($_GET['x']);

function foo($d) {
    $e = $d;
    foo2($d);
    shell_exec($d);
}

function foo2($d2) {
    $e2 = $d2;
    foo3($d2);
    shell_exec($d2);
}

function foo3($d3) {
    $e3 = $d3;
    foo4($d3);
    shell_exec($d3);
}

function foo4($d4) {
    $e4 = $d4;
    foo4($d4);
    shell_exec($d4);
    shell_exec($dd5);
}
?>
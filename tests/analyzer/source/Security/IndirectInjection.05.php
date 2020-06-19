<?php

$a = $_GET['x'];
foo1($a);
$b = 3;
foo1($b);

function foo1($a) {
    shell_exec($a);
    foo2($a);
}

function foo2($b) {
    shell_exec($b);
    foo3($b);
}

function foo3($c) {
    shell_exec($c);
    foo3($c, $c);
}

function foo4($d0, $d1) {
    shell_exec($d0);
    foo5(0, $d1);
}

function foo5($e0, $e1) {
    shell_exec($e0);
    shell_exec($e1);
    foo6($e1, 0, 1, 3);
    foo6(0, $e1, 2, 3);
    foo6(0, 1, $e1, 3);
    foo6(0, 1, 2, $e1);
}

function foo6($f0, $f1, $f2, $f3) {
    shell_exec($f0);

    $f12 = $f1;
    shell_exec($f12);

    $f21 = $f2;
    $f22 = $f21;
    shell_exec($f22);

    $f31 = $f3;
    $f32 = $f31;
    $f33 = $f32;
    shell_exec($f33);
    
    foo7(0, $e4);
}

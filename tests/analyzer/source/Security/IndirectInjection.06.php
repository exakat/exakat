<?php

$a = $_GET['x'];
foo6($a, 0, 1, 2, 3);
foo6(0, $a, 1, 2, 3);
foo6(0, 1, $a, 2, 3);
foo6(0, 1, 2, $a, 3);
foo6(0, 1, 2, 3, $a);

$b = 3;
foo1($b);

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

<?php

function foo(array $a,
             array $b,
             array $c,
             array $d,
             array $e,
             ) {
    echo $a[1];
    echo $b[1] ?? '';
    echo $c[2] ?: '';
    echo $d[3] ? "c" : '';
    $e[] = 1;
}
?>
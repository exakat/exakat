<?php

function foo() {
    $a = [];
    $a[3][] = 3;

    $c = [];
    $c[] = 3;
    $c[] = 3;
    $c[] = 3;
    $c[] = 3;
    $c[] = 3;
    $c[] = 3;

    $c2 = [];
    $c2[] = 3;
    print array_sum($c2);

    $b = 3;
    $b += 3;

    $d = 3;
    $d += foo();
}
?>
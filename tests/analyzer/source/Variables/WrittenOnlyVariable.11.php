<?php

function foo() {
    global $a, $c;
    $a = [];
    $a[3][] = 3;

    $c = [];
    $c[] = 3;
    print array_sum($c);
}
?>
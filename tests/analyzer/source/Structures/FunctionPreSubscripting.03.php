<?php

function foo() {
    $a = goo();
    $b = goo();
    $c = goo();
    $d = goo();

    echo $a[1];
    echo $b[1].$b[2];
    // no c
    echo $d[1].$d[2].$d[3].$d[4];
}
?>
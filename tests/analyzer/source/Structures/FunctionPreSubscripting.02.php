<?php

function foo() {
    $a = goo();
    echo $a[1];
    
    $b = goo();
    echo $b[1].$b[2];

    $c = goo();

    $d = goo();
    echo $d[1].$d[2].$d[3].$d[4];
}
?>
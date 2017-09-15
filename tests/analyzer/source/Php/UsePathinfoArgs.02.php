<?php

function foo_ok() {
    $a = pathinfo($file1, PATHINFO_DIRNAME);
    echo $a;
}

function foo_d() {
    $a = pathinfo($file2);
    echo $a['dirname'];
    echo $a['basename2'];
}

function foo_() {
    $a = pathinfo($file3);
    return $a;
}

function foo_deb() {
    $a = pathinfo($file4);
    echo $a['dirname'];
    echo $a['extension'];
    echo $a['basename'];
    echo $a['basename'];
    echo $a['basename'];
    echo $a['x'];
}

?>
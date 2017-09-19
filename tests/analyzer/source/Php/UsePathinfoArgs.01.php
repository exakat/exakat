<?php

function foo_ok() {
    $a = pathinfo($file1, PATHINFO_DIRNAME);
    echo $a;
}

function foo_db() {
    $a = pathinfo($file2);
    echo $a['dirname'];
    echo $a['basename'];
}

function foo_de() {
    $a = pathinfo($file3);
    echo $a['dirname'];
    echo $a['extension'];
}

function foo_deb() {
    $a = pathinfo($file4);
    echo $a['dirname'];
    echo $a['extension'];
    echo $a['basename'];
}

function foo_d() {
    $a = pathinfo($file5);
    echo $a['dirname'];
}

function foo_ddd() {
    $a = pathinfo($file6);
    echo $a['dirname'];
    echo $a['dirname'];
    echo $a['dirname'];
}

?>
<?php

function foo() {
    $a = hash_init('md5');
    if (is_resource($a)) {
        hash_update($message);
    }
}

function foo2() {
    $a2 = hash_init('md5-2');
    if (!is_resource($a2)) {
        return;
    }
    return hash_update($message);
}

function foo3() {
    $a3 = hash_init('md5-3');
    if (is_object($a3)) {
        hash_update($message);
    }
}
?>
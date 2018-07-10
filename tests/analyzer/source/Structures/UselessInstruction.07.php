<?php

function x() {
    return $a['b']++;
}

function x2() {
    return ++$a; // OK
}

?>
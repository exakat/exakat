<?php

function x() {
    $reachable1++;
    assert(false, 'erreur reported');
    $unreachable1++;
}

function x2() {
    if ($x2) {
        $reachable2++;
        assert(false, 'erreur reported');
        $unreachable2++;
    }
    $reachable21++;
}

function y() {
    $reachable3++;
    assert(false, 'erreur reported');
    $unreachable3++;
}

function z() {
    $reachable4++;
    assert($x, 'erreur reported ?');
    $reachable41++;
}

function zz() {
    $reachable5++;
    assert(3, 'erreur not reported');
    $reachable51++;
}

?>
<?php

function x() {
    $reachable1++;
    return true;
    $unreachable1++;
}

function x2() {
    if ($x2) {
        $reachable2++;
        return true;
        $unreachable2++;
    }
    $reachable21++;
}

function y() {
    $reachable3++;
    return true;
    $unreachable3++;
}

function y2() {
    if ($y2) {
        $reachable42++;
        throw $e;
        $unreachable4++;
    }
    $reachable521++;
}

function z() {
    $reachable6++;
    exit();
    $unreachable5++;
}

function z2() {
    if ($y2) {
        $reachable72++;
        die();
        $unreachable6++;
    }
    $reachable821++;
}

?>
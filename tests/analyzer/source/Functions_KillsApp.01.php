<?php

function KillApp() {
    $a++;
    die();
}

function willKillApp() {
    $b++;
    KillApp();
    $unreachable_code++;
}

function willKillApp2ndround() {
    $b++;
    if ($y) {
        KillApp();
    } else {
        $c++;
    }
    $reachable_code++;
}

function willNotApp2ndround() {
    $b++;
    if ($y) {
        NoKillApp();
    } else {
        $c++;
    }
    $reachable_code++;
}


?>
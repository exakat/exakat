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

function willNotKillApp() {
    $b++;
    if ($y) {
        KillApp();
    } else {
        $c++;
    }
    $reachable_code++;
}


?>
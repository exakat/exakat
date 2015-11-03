<?php

function willNotKillApp() {
    if ($a++) { 
        die();
    }
}

function willNotKillApp2() {
    if ($b++) 
        KillApp();
    $unreachable_code++;
}

function KillApp() {
    exit();
}

?>
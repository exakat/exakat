<?php
$initTime = 1;
$global = 2;

function status($path) {
    global $initTime;
    global $readOnly;
    
    $initTime = $global = $local = 3;
    echo $readOnly;
}
?>
<?php

function x() {
    $written_only = 10;
    $written_only++;
    
    $rw_var = 1;
    $other_wo_var = $rw_var + 4;

    $rw_var2 = 1;
    $other_wo_var = $rw_var2++ + 4;
    
    $different_scopes = 1;
}

$different_scopes = 2;

?>
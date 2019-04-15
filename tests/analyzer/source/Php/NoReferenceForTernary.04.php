<?php


function foo(&$a, $d) {
    $c = rand() ?: $a; 
    $f = rand() ?: $d; 
}

?>
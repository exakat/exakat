<?php

function foo($e) {
    $a = 1;
    try{
        $b = 2 + $c;
        $d = 3;
        $f = 1;
    } catch (Exception $e) {
        echo $a + $b + $c + $f;
    }
}
?>
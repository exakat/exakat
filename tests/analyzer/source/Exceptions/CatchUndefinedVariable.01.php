<?php

function foo($e) {
    $a = 1;
    try{
        $b = 2 + $c;
        $d = 3;
        $e = 1;
    } catch (Exception $e) {
        echo $a + $b + $c + $e;
    }
}
?>
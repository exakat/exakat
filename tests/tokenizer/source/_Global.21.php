<?php


function foo() {
    $c = $_GET[1] + $argv;
    
    global $$c, ${$c.'d'}, $e;
}
?>
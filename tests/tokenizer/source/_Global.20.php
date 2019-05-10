<?php

global $c, $d, $f;

function foo() {
    global $c, $e;
    
    $GLOBALS['f'] = 1;
}

function foo1() {
    global $c, $e, $argv, $_ENV;
    
    $_POST = 3;
    $_get = 4;
    $GLOBALS['f'][3] = 1;
}

?>
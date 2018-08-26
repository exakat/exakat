<?php

function foo($arg) {
    global $global;
    $local = 2;
    static $static;
    
    $array[3] = 4 ;
    $object->a = 3;
    
    compact('global', 'static', 'local', 'arg', 'array', 'object', 'undefined');
}

?>
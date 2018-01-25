<?php

$lang = 1;
$lang2 = 1;
$globalButLocal = 3;

class x {
    public $lang = 2, $lang2;
    
    function foo() {
        global $globalVariable;
    
        $globalVariable = 2;

        $globalButLocal = 1;
    
        $localOnly = 3;
        
        $lang = 4;
        $lang2 = 3;
    }

    function variablesButNoGLobal() {
        $a = 1;
    }

    function globalsButNoVariable() {
        global $a;
    }
}

?>
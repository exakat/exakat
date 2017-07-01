<?php

class foo {
    function normal($a, $b) {}
    
    function variableArguments() { 
        $x = func_get_args();
    }
}

$a->normal();
$a->normal(1);
$a->normal(2, 3);
$a->normal(4, 5, 6);

foo::variableArguments();
foo::variableArguments(1);
foo::variableArguments(2, 3);
foo::variableArguments(4, 5, 6);

?>

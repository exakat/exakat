<?php

function normal($a, $b) {}

function variableArguments() { 
    $x = func_get_args();
}

normal();
normal(1);
normal(2, 3);
normal(4, 5, 6);

variableArguments();
variableArguments(1);
variableArguments(2, 3);
variableArguments(4, 5, 6);

?>

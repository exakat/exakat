<?php

$variable = 1;

function x($argument_definition) {
    $other_variable = 2;
}

x($calling_argument);

interface i {
    function interfaceMethod1 ($interfaceArgument1); 
    function interfaceMethod2 ($interfaceArgument2 = 2); 
    function interfaceMethod21 (x $interfaceArgument21, t $interfaceArgument22 = null); 
}
?>
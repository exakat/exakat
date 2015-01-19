<?php
function a($arrayWithIndex, $arrayAppend, $callable, $nothing, $objectForProperty, $objectForMethod) {
    $objectForProperty->x;
    $objectForMethod->x();
    
    $callable();
    
    $arrayWithIndex[1] = 1;
    $arrayAppend[] = 2;
    
    $nothing = $nothing + 1;
}

?>
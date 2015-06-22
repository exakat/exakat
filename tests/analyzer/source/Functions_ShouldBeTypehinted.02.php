<?php
function a($arrayWithIndex, $arrayAppend, $callable, $nothing, $objectForProperty, $objectForMethod) {
    $objectForProperty->x;
    $objectForMethod->x();
    
    $callable();
    
    $arrayWithIndex['1'] = 1;
//    $arrayWithIndex[1] = 1; numeric are avoided because they may be strings are avoided 
    $arrayAppend[] = 2;
    
    $nothing = $nothing + 1;
}

?>
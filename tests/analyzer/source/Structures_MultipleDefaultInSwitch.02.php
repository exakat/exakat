<?php

switch ($one) {
    case 1: 
        break 1;
        
    default : 
        break 1;
}

switch ($two) {
    default : 
        break 1;

    case 1: 
        break 1;
        
    default : 
        break 1;

    case 1: 
        break 1;
}

switch ($oneNested) {
    case 1: 
        break 1;
        
    default : 
    switch ($oneNested2) {
        case 1: 
            break 1;
           
        default : 
            break 1;
    }
}

?>
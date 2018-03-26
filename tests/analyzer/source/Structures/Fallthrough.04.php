<?php

switch($withFallthroughCase) {
    case 1 : 
        break ;
    case 2 : 
        break ;
    case 3 : 
        ++$a;
    case 4 : 
        break ;
}

switch($withFallthroughDefault) {
    case 1 : 
        break ;
    case 2 : 
        break ;
    default: 
        ++$a;
    case 4 : 
        break ;
}

switch($with2FallthroughCaseDefault) {
    case 1 : 
        break ;
    case 2 : 
    default: 
        ++$a;
    case 4 : 
        break ;
}

switch($withoutFallthrough) {
    case 1 : 
        break ;
    case 2 : 
        break ;
    default: 
        break 1;
    case 4 : 
        break ;
}
?>
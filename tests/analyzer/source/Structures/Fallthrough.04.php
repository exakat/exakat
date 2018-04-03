<?php

switch($withFallthroughCase) {
    case 1 : 
        die() ;
    case 2 : 
        die() ;
    case 3 : 
        ++$a;
    case 4 : 
        die() ;
}

switch($withFallthroughDefault) {
    case 1 : 
        die() ;
    case 2 : 
        die() ;
    default: 
        ++$a;
    case 4 : 
        die() ;
}

switch($with2FallthroughCaseDefault) {
    case 1 : 
        die() ;
    case 2 : 
    default: 
        ++$a;
    case 4 : 
        die() ;
}

switch($withoutFallthrough) {
    case 1 : 
        die() ;
    case 2 : 
        die() ;
    default: 
        die() 1;
    case 4 : 
        die() ;
}
?>
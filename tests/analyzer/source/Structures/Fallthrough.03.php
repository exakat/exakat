<?php

switch($withFallthrough) {
    case 1 : 
        break ;
    case 2 : 
        break ;
    default: 
        ++$a;
    case 4 : 
        break ;
}


switch($withExplicitFallthrough) {
    case 1 : 
    case 2 : 
        break ;
    case 4 : 
        break ;
}

?>
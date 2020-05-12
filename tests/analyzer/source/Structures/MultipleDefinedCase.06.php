<?php

switch($a) {
    case true : 
    case 1 : 
    case 2 : 
    case 3 : 
}

switch($b) {
    case true : 
    case 2 : 
    case 3 : 
    default : 
}

switch($c) {
    case 1 : 
        break 1;
    case true : 
        break 1;
    case 0 : 
        break 1;
    case false : 
}

?>
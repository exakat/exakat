<?php

// multiple default in switch
switch ($zero) {
    case 1: 
        break 1;
}

switch ($one) {
    case 1: 
        break 1;
        
    default : 
        break 1;
}

switch ($oneBis) {
    default : 
        break 1;

    case 1: 
        break 1;
}

switch ($two) {
    case 1: 
        break 1;
        
    default : 
        break 1;

    default : 
        break 1;
}

switch ($three) {
    default : 
        break 1;

    case 1: 
        break 1;

    default : 
        break 1;

    default : 
        break 1;
}

switch ($four) {
    default : 
        break 1;

    default : 
        break 1;

    case 1: 
        break 1;

    default : 
        break 1;

    default : 
        break 1;
}

?>
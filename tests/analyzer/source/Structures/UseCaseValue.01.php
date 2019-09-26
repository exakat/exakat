<?php

// use the literal, not the variable
switch($c) {
    case 'a' : 
        echo $b[$c];
        break;

    case 'b' : 
        echo $c = 2;
        break;

    case 'c' : 
        echo $d = 2;
        break;

    default : 
        echo $c;
        break;
        
        
}

?>
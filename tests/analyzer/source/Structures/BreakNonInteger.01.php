<?php

switch($x) {
    case 1: 
        break 1;

    case 0: 
        break 0;

    case -1: 
        break -1;

    case 2: 
        break 1 + 1;

    case "3": 
        break "3";

    case "0": 
        break "0";

    case 'null': 
        break null ;

    default: 
        break $x;

}

?>
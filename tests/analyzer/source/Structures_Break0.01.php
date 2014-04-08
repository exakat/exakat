<?php

switch($x) {
    case 1: 
        break 1;

    case 0: 
        break 0;

    case 'null': 
        break null ; // not spotted, but really ? 

    default: 
        break $x;

}

?>
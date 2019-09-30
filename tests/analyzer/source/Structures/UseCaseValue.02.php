<?php

switch($x) {
    case 1: 
        echo $x;
        break;

    case 2: 
        echo $x;
        break;
}

switch($y) {
    case 3 :
    case 4 : 
        echo $y;
        break;

}

switch($z) {
    default :
    case 5 : 
        echo $y;
        break;

}

switch($a) {
    default :
        break;
    case 6 : 
        echo $a;
        break;

}

?>
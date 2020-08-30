<?php

switch($b1) {
    case 1 :
        ++$a1;
        break;

    default:
    case 6:
        ++$a6;
}

switch($b2) {
    case 4:
        ++$a4;
        
    case 2:
    case 3:
        ++$a2;
        ++$a3;
        break;
}

switch($b3) {
    case 2:
    case 3:
        ++$a2;
        ++$a3;
        break;

    case 6:
        ++$a6;
}

?>

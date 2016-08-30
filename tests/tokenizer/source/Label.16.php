<?php

switch($t) {
    case T_LNUMBER:  // integers
        $a++;
        goto T_C;
    case T_B:  // integers
        $a++;

    default:
        echo 'a';
}

T_C:
echo 'fin';
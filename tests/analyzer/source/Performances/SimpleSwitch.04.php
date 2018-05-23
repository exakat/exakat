<?php

        switch ($a->b1) {
        case A:
            $a = 1;
            break;
        case \B:
            $a = 1;
            break;
        case C::D:
            $a = 1;
            break;
        case 1:
            $a = 1;
            break;
        case 'ALL':
            $a = 1;
            break;
        default:
            throw new Exception("Invalid");
        }

        switch ($a->b2) {
        case C::method():
            $a = 1;
            break;
        }

        switch ($a->b3) {
        case C::$d:
            $a = 1;
            break;
        }

?>
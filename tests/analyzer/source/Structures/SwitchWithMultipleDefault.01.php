<?php

    switch ($zero) { 
        case 1: 
    }

    $a++;

    switch ($one) { 
        default: 
    }

    $a++;

    switch ($two) { 
        default: 
        default: 
    }

    $a++;

    switch ($three) { 
        default: 
        default: 
        default: 
    }

    switch ($three_but_nested) { 
        default: 
        switch ($two_and_nested) {
            default: 
            default: 
        }
    }

    $a++;

    switch ($three2) { 
        case 1 : 
            break 1;
        default: 
        case 2 : 
            break 2;
        default: 
        case 3 : 
            break 3;
        default: 
        case 4 : 
            break 4;
    }
    
?>
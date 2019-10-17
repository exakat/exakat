<?php

switch($a) {
    case 1: 
        break 1;
        
    case 2: 
        break;

    case 3: 
        switch($a) {
            case 11: 
                break 1;
                
            case 21: 
                break;
        
            case 33: 
                break;
                
        }

        break;
        
}
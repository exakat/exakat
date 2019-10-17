<?php

switch($a) {
    case 1: 
        break 1;
        
    case 2: 
        break;

    case 3: 
        switch($a2) {
            case 11: 
            case 1111:
                break 1;
                
            case 21: 
                switch($a3) {
                    case 111: 
                        break 1;
                        
                        
                    case 211: 
                        break;
                
                    default:
                    case 331: 
                        break;
                        
                }
                break;
        
            case 33: 
                break;
                
        }

        break;
        
}
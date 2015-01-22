<?php 
    $GLOBALS['usedOnce1'] = 2;
    global $usedOnce2;
    $usedOnce2 = 2;
    
    $GLOBALS['usedTwiceGg'] = 1;
    $GLOBALS['usedTwiceGG'] = 1;
    
    global $usedTwicegg;
    global $usedTwicegG;
    $usedTwicegg = 2;
    $usedTwicegG = 3;
    
    function y() {
        global $usedTwiceGg;
        global $usedTwicegg;
        
        $usedTwiceGg = 1;
        $usedTwicegg = 2;
        
        $GLOBALS['usedTwicegG'] = 2;
        $GLOBALS['usedTwiceGG'] = 2;
    }
    
?>
<?php

unusedLabel: 
usedInYLabel : 

usedInGlobalLabel : 

goto usedInGlobalLabel;

class x {
    function y() {
        usedInYLabel : 
    
        unusedInYLabel : 
    
        usedLabel2 : 
        goto usedInYLabel;
        goto usedLabel2;
        goto usedLabel2;
    }
}
?>
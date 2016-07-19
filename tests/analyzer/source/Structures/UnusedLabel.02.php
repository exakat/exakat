<?php

function x() {
    unusedLabel: 

    usedInYLabel : 
}

function y() {
    usedInYLabel : 
    
    usedLabel2 : 

    goto usedInYLabel;
    goto usedLabel2;
    goto usedLabel2;
}


 ?>
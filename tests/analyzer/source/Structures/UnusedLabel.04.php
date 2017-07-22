<?php

unusedLabel: 
usedInYLabel : 

usedInGlobalLabel : 

goto usedInGlobalLabel;

function ($a = 3) {
    usedInYLabel : 

    unusedInYLabel : 

    usedLabel2 : 
    goto usedInYLabel;
    goto usedLabel2;
    goto usedLabel2;
};

?>
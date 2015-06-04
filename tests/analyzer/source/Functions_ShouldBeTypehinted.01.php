<?php

function xp ($string, $array1, $objectp1) {
    $string .= 'a';
    $array1['a'] = 3;
    
    $objectp1->property;
    $objectm1->method();
}

function xm ($string, $array2, $objectm2) {
    $string .= 'a';
    $array2[] = 3;
    
    $objectp2->property;
    $objectm2->method();
}

function xmp ($string, $array3, $objectm3, $objectp4) {
    $string .= 'a';
    $array3['b']();
    
    $objectp4->property;
    $objectm3->method();
}

function xmp2 ($string32, $array32, $objectm32, $objectp32) {
    $string .= 'a';
    $array32['c']['d']();
}

function xpm ($string, $array4, $objectp5, $objectm6) {
    $string .= 'a';
    $array["f"] = 3;
    
    $objectp5->property;
    $objectm6->method();
}

?>
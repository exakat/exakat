<?php

function xp ($string, $array, $objectp1) {
    $string .= 'a';
    $array[3] = 3;
    
    $objectp1->property;
    $objectm1->method();
}

function xm ($string, $array, $objectm2) {
    $string .= 'a';
    $array[3] = 3;
    
    $objectp2->property;
    $objectm2->method();
}

function xmp ($string, $array, $objectm3, $objectp4) {
    $string .= 'a';
    $array[3] = 3;
    
    $objectp4->property;
    $objectm3->method();
}

function xpm ($string, $array, $objectp5, $objectm6) {
    $string .= 'a';
    $array[3] = 3;
    
    $objectp5->property;
    $objectm6->method();
}

?>
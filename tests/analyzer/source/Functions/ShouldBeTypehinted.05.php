<?php

function xp ($string1, $array1, $objectp1) {
    $string1 .= 'a';
    $array1['a'] = 3;
    
    $objectp1->property;
    $objectp1->property2;
    $objectp1->property3;
    $objectp1->property4;
    $objectp1->property5;
    $objectp1->property6;
    $objectp1->property7;
    $objectm1->method();
}

function xp2 ($string2, $array2, someObject $objectp2) {
    $string .= 'a';
    $array1['a'] = 3;
    
    $objectp1->property;
    $objectp1->property2;
    $objectp1->property3;
    $objectp1->property4;
    $objectp1->property5;
    $objectp1->property6;
    $objectp1->property7;
    $objectm1->method();
}

?>
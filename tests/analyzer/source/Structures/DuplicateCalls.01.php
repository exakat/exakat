<?php 

function foo() {
    $a = $duplicate->c();
    if ($duplicate->c()) {}

    $a = $duplicate3->c();
    if ($duplicate3->c()) { $duplicate3->c();}
    
    $duplicateInTwoFunctions->c();
    
    $a->duplicateMethod(1,2,3);
    $b->duplicateMethod(1,2,3);
    
}

function bar() {
    $duplicateInTwoFunctions->c();
}
?>
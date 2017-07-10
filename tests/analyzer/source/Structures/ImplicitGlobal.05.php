<?php

global $explicitGlobalVar; // explicit global variable
$implicitGlobalVar; // naturally global var

class xy {
    function x2 ($a) {
        global $explicitGlobalVar, $implicitGlobalVar;
        
    }
    
    function x3 ($b) {
        global $explicitGlobalVar, $implicitGlobalVar;
    }
}
?>
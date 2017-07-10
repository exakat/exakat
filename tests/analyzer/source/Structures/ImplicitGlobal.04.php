<?php

global $explicitGlobalVar; // explicit global variable
$implicitGlobalVar; // naturally global var

function ($a) {
    global $explicitGlobalVar, $implicitGlobalVar;
    
};

function ($b) {
    global $explicitGlobalVar, $implicitGlobalVar;
};

?>
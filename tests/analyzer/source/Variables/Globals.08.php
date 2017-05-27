<?php

$implicitGlobal = 1;
global $explicitGlobal;
$explicitGlobal['a']++;
$GLOBALS['globalInGLOBALS2']['b'] = 1;
$GLOBALS = [3];

function a() {
    global $explicitGlobalFunction;
    
    $explicitGlobalFunction['d']++;
    $GLOBALS['globalInGLOBALS']['c'] = 1;

    $localVariableFunction = 2;
    $_POST[3];
}
?>
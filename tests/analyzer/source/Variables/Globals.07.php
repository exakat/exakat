<?php

$implicitGlobal = 1;
global $explicitGlobal;
$explicitGlobal[] = 2;
$GLOBALS['globalInGLOBALS2'][] = 3;
$GLOBALS = [3];

function a() {
    global $explicitGlobalFunction;
    
    $explicitGlobalFunction[] = 1;
    $GLOBALS['globalInGLOBALS'][] = 4;

    $localVariableFunction = 2;
    $_POST[3];
}
?>
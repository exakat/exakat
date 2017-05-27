<?php

$implicitGlobal = 1;
global $explicitGlobal;
$explicitGlobal->B;
$GLOBALS['globalInGLOBALS2']->b = 1;
$GLOBALS = [3];

function a() {
    global $explicitGlobalFunction;
    
    $explicitGlobalFunction->A;
    $GLOBALS['globalInGLOBALS']->a = 1;

    $localVariableFunction = 2;
    $_POST[3];
}
?>
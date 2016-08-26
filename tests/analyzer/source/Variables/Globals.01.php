<?php

$implicitGlobal = 1;
global $explicitGlobal;
$GLOBALS['globalInGLOBALS2'] = 1;
$GLOBALS = [3];

function a() {
    global $explicitGlobalFunction;
    $GLOBALS['globalInGLOBALS'] = 1;

    $localVariableFunction = 2;
    $_POST[3];
}
?>
<?php

$implicitGlobal = 1;
global $explicitGlobal;
$explicitGlobal++;
$GLOBALS['globalInGLOBALS2'] = 1;
$GLOBALS = [3];

class x {
function a() {
    global $explicitGlobalFunction;
    
    $explicitGlobalFunction++;
    $GLOBALS['globalInGLOBALS'] = 1;

    $localVariableFunction = 2;
    $_POST[3];
}
}
?>
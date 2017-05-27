<?php

$expected     = array('$explicitGlobal',
                      '$implicitGlobal',
                      '$GLOBALS[\'globalInGLOBALS2\']',
                      '$GLOBALS[\'globalInGLOBALS\']',
                      '$explicitGlobalFunction',);

$expected_not = array('$localVariableFunction',
                      '$GLOBALS',
                      '$_POST');

?>
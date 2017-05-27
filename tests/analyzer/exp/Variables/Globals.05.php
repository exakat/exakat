<?php

$expected     = array('$explicitGlobal',
                      '$implicitGlobal',
                      '$GLOBALS[\'globalInGLOBALS2\']',
                      '$GLOBALS[\'globalInGLOBALS\']',
                      '$explicitGlobalFunction',
                      '$x');

$expected_not = array('$localVariableFunction',
                      '$GLOBALS',
                      '$_POST');

?>
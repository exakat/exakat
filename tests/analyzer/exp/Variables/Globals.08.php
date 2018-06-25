<?php

$expected     = array('$explicitGlobal',
                      '$explicitGlobal',
                      '$implicitGlobal',
                      '$GLOBALS[\'globalInGLOBALS2\']',
                      '$GLOBALS[\'globalInGLOBALS\']',
                      '$explicitGlobalFunction',
                      '$explicitGlobalFunction',
                      '$GLOBALS',
                     );

$expected_not = array('$localVariableFunction',
                      '$_POST',
                     );

?>
<?php

$expected     = array('$explicitGlobal',
                      '$explicitGlobal',
                      '$implicitGlobal',
                      '$GLOBALS[\'globalInGLOBALS2\']',
                      '$GLOBALS[\'globalInGLOBALS\']',
                      '$explicitGlobalFunction',
                      '$explicitGlobalFunction',
                      '$x',
                     );

$expected_not = array('$localVariableFunction',
                      '$GLOBALS',
                      '$_POST',
                     );

?>
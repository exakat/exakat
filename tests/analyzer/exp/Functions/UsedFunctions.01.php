<?php

$expected     = array('function definedFunction( ) { /**/ } ',
                     );

$expected_not = array('function undefinedFunction( ) { /**/ } ',
                      'function definedMethodUsedAsFunction( ) { /**/ } ',
                      'function definedMethod( ) { /**/ } ',
                      'function definedStaticMethod( ) { /**/ } ',
                     );

?>
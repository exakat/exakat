<?php

$expected     = array('function deepDefinedLevel2( ) { /**/ } ',
                      'function deepDefinedLevel2( ) { /**/ } ',
                      'function deepDefinedLevel2( ) { /**/ } ',
                      'function deepDefinedFunction( ) { /**/ } ',
                      'function deepDefinedFunction( ) { /**/ } ',
                      'function deepDefinedFunction( ) { /**/ } ',
                      'class deepDefinedClass { /**/ } ',
                      'interface deepDefinedInterface { /**/ } ',
                      'interface deepDefinedTrait { /**/ } ',
                     );

$expected_not = array('function classLevel( ) { /**/ } ',
                     );

?>
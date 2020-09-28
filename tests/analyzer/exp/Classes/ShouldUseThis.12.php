<?php

$expected     = array('static public function getCalledClassWithArg($a) { /**/ } ',
                     );

$expected_not = array('function withThis( ) { /**/ } ',
                      'function getCalledClass( ) { /**/ } ',
                      'static public function getCalledClassWithoutThis( ) { /**/ } ',
                      'function getClassMethods( ) { /**/ } ',
                     );

?>
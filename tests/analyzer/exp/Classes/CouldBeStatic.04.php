<?php

$expected     = array('function couldBeStatic( ) { /**/ } ',
                     );

$expected_not = array('static function isAbstract( ) { /**/ } ',
                      'function couldNotBeStatic( ) { /**/ } ',
                      'function ($closure) { /**/ } ',
                      'function aFunction( ) { /**/ } ',
                     );

?>
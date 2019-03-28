<?php

$expected     = array('function couldBeStatic( ) { /**/ } ',
                     );

$expected_not = array('static function isStatic( ) { /**/ } ',
                      'function couldNotBeStatic( ) { /**/ } ',
                      'function ($closure) { /**/ } ',
                      'function aFunction( ) { /**/ } ',
                     );

?>
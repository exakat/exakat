<?php

$expected     = array('function couldBeStatic( ) { /**/ } ',
                     );

$expected_not = array('function couldNotBeStatic( ) { /**/ } ',
                      ' function ($closure) { /**/ } ',
                      'function aFunction( ) { /**/ } ',
                     );

?>
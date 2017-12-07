<?php

$expected     = array('function foo2( ) { /**/ } ',
                      'function foo4( ) { /**/ } ',
                     );

$expected_not = array('function foo( ) { /**/ } ',
                      'function foo3( ) { /**/ } ',
                      'function foo4( ) { /**/ } ',
                      'function foo6( ) { /**/ } ',
                     );

?>
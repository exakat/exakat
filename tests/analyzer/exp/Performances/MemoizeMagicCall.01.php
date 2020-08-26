<?php

$expected     = array('function foo2( ) { /**/ } ',
                      'function foo3( ) { /**/ } ',
                      'function foo4( ) { /**/ } ',
                     );

$expected_not = array('function foo1( ) { /**/ } ',
                      'function foo_split1( ) { /**/ } ',
                      'function foo_split2( ) { /**/ } ',
                      'function foo_rw( ) { /**/ } ',
                     );

?>
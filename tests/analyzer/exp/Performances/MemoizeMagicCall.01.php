<?php

$expected     = array('function foo4( ) { /**/ } ',
                      'function foo2( ) { /**/ } ',
                      'function foo3( ) { /**/ } ',
                     );

$expected_not = array('function foo1( ) { /**/ } ',
                      'function foo_split1( ) { /**/ } ',
                      'function foo_split2( ) { /**/ } ',
                      'function foo_rw( ) { /**/ } ',
                     );

?>
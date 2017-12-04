<?php

$expected     = array('function foo( ) { /**/ } ',
                      'class foo { /**/ } ',
                     );

$expected_not = array('function foo( $i ) { /**/ } ',
                      'function foo( $c ) { /**/ } ',
                      'function foo( $t ) { /**/ } ',
                      'function ( $x ) { /**/ } ',
                      'class { /**/ } ',
                     );

?>
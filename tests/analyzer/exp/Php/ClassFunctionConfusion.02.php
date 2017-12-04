<?php

$expected     = array('function foo( ) { /**/ } ',
                      'interface foo { /**/ } ',
                     );

$expected_not = array('function foo( $i ) { /**/ } ',
                      'function foo( $c ) { /**/ } ',
                      'function foo( $t ) { /**/ } ',
                      'function ( $x ) { /**/ } ',
                     );

?>
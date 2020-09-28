<?php

$expected     = array('function ( ) use ($a, ) { /**/ } ',
                      'function ( ) use ($a, $d, ) { /**/ } ',
                      'function ( ) use ($a, $c, ) { /**/ } ',
                     );

$expected_not = array('function ( ) use ($a ) { /**/ } ',
                      'function ( ) use ($a, $b ) { /**/ } ',
                     );

?>
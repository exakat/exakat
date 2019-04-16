<?php

$expected     = array('function ( ) use ($m) { /**/ } ',
                     );

$expected_not = array('function ( ) use ($e) { /**/ } ',
                      'function ( ) use ($l) { /**/ } ',
                      'function ( ) use ($m2) { /**/ } ',
                      'function ( ) use (&$m) { /**/ } ',
                      'function (N $a) { /**/ } ',
                     );

?>
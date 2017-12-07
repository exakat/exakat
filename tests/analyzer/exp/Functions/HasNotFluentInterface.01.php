<?php

$expected     = array('function nonFluent1( ) { /**/ } ',
                      'function nonFluent2( ) { /**/ } ',
                      'function nonFluent3( ) { /**/ } ',
                      'function __get($name) { /**/ } ',
                     );

$expected_not = array('function fluent( ) { /**/ } ',
                     );

?>
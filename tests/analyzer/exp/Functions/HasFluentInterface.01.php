<?php

$expected     = array('function fluent( ) { /**/ } ',
                     );

$expected_not = array('function nonFluent( ) { /**/ } ',
                      'function nonFluent2( ) { /**/ } ',
                      'function nonFluent( ) { /**/ } ',
                      'function __get($name) { /**/ } ',
                     );

?>
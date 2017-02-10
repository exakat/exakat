<?php

$expected     = array('function __clone( ) { /**/ } ',
                      'function __constructor( $inX ) { /**/ } ');

$expected_not = array('function __constructor( ) { /**/ } ',
                      'function __destructor( ) { /**/ } ',
                      'function usableReturn( ) { /**/ } ');

?>
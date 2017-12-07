<?php

$expected     = array('function __clone( ) { /**/ } ',
                      'function __construct($inX) { /**/ } ',
                     );

$expected_not = array('function __constructor( ) { /**/ } ',
                      'function __destructor( ) { /**/ } ',
                      'function usableReturn( ) { /**/ } ',
                     );

?>
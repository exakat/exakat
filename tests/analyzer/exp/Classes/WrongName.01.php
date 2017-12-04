<?php

$expected     = array('function __constructor( ) { /**/ } ',
                      'function __destructor( ) { /**/ } ',
                      'function __bar( ) { /**/ } ',
                     );

$expected_not = array('function __construct( ) { /**/ } ',
                      'function __destruct( ) { /**/ } ',
                      'function usableReturn( ) { /**/ } ',
                     );

?>
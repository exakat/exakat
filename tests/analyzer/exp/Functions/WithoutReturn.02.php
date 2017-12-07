<?php

$expected     = array('function noReturn( ) { /**/ } ',
                     );

$expected_not = array('function __construct( ) { /**/ } ',
                      'function __destruct( ) { /**/ } ',
                      'function __wakeup( ) { /**/ } ',
                      'function withReturn( ) { /**/ } ',
                     );

?>
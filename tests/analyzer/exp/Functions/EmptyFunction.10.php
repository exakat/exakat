<?php

$expected     = array('function lastEmpty( ) { /**/ } ',
                     );

$expected_not = array('function lastNotEmpty( ) { /**/ } ',
                      'function lastReturnOneEmpty( ) { /**/ } ',
                      'function doubleReturn( ) { /**/ } ',
                      'function __construct( ) { /**/ } ',
                     );

?>
<?php

$expected     = array('function __construct($x) { /**/ } ',
                      'function __destruct( ) { /**/ } ',
                     );

$expected_not = array('function __construct( ) ',
                      'function __destruct( ) ',
                      'function usableReturn( ) ',
                      'function usableReturnX( ) { /**/ } ',
                     );

?>
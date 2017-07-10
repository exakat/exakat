<?php

$expected     = array('function __construct( ) { /**/ } ', 
                      'function __construct( ) { /**/ } ', 
                      'function __construct( ) { /**/ } ', 
                      'function __construct( ) { /**/ } ');
                      
// Only 4 constructors. The 3 others are not constructors.

$expected_not = array('function __construct($a) { /**/ }');

?>
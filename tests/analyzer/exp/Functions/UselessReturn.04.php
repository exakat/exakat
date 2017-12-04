<?php

$expected     = array('function __construct( ) { /**/ } ',
                      'function __construct($z) { /**/ } ',
                      'function __clone( ) { /**/ } ',
                     );

$expected_not = array('function __construct($y) { /**/ } ',
                      'function __destructor( ) { /**/ } ',
                     );

?>
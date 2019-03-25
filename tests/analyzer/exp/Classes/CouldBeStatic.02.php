<?php

$expected     = array('function couldBeStatic($c) { /**/ } ', 
                     );

$expected_not = array('function __clone( ) { /**/ } ', 
                      'function __construct( ) { /**/ } ', 
                      'function __other( ) { /**/ } ', 
                      'function constant( ) { /**/ } '
                     );

?>
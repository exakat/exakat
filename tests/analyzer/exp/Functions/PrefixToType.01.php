<?php

$expected     = array('function isHash2( ) { /**/ } ',
                      'function hasHash2( ) { /**/ } ',
                      'function hasNotHash2( ) { /**/ } ',
                      'function hasNotHash5( ) : ?int { /**/ } ',
                     );

$expected_not = array('function isHashFunction( ) { /**/ } ',
                      'function hasNotHash4() : ?bool { /**/ } ',
                     );

?>
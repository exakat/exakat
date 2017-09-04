<?php

$expected     = array('public function __toString( ) { /**/ } ',
                     );

$expected_not = array('public function __toSTRING( ) { /**/ } ',
                      'public function __TOString( ) { /**/ } ',
                      'function y( ) { /**/ } ',
                     );
?>
<?php

$expected     = array('function x( ) { /**/ } ',
                      'static function x2( ) { /**/ } ',
                      'final function x3( ) { /**/ } ',
                     );

$expected_not = array('final public function x3p( ) { /**/ } ',
                      'public final function px3( ) { /**/ } ',
                     );

?>
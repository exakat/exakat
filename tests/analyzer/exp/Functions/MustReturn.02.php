<?php

$expected     = array('public function __sleep( ) { /**/ } ',
                      'public function __isset($a) { /**/ } ',
                      'public function __GET($b) { /**/ } ',
                     );

$expected_not = array('function __set_state( ) { /**/ } ',
                      'public function __isset ( $a ) { /**/ } ',
                      'public abstrct function __get( $b ) { /**/ } ',
                     );

?>
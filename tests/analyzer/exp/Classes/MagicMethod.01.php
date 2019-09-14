<?php

$expected     = array('public function __toString( ) { /**/ } ',
                      'public function __call($a, $b) { /**/ } ',
                     );

$expected_not = array('public function __toBoolean( ) { /**/ } ',
                      'public function __DESTRUCT( ) { /**/ } ',
                      'public function __Construct( ) { /**/ } ',
                     );

?>
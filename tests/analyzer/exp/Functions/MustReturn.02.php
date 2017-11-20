<?php

$expected     = array('public function __sleep( ) { /**/ } ', 
                      'public function __isset($a) { /**/ } ',
                     );

$expected_not = array('function __set_state ( ) { /**/ } ',
                      'public function __isset ( $a ) { /**/ } ',
                     );

?>
<?php

$expected     = array('function  ( ) { /**/ } ',
                     );

$expected_not = array('public function dontGetClosure( ) { /**/ } ',
                      'function  ($value) { /**/ } ',
                      );

?>
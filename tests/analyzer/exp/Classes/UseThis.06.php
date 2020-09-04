<?php

$expected     = array('static public function getCalledClass( ) { /**/ } ',
                      'public function getCalledClassWithThis2( ) { /**/ } ',
                     );

$expected_not = array('function nothing( ) { /**/ } ',
                      'static public function getCalledClassWithArg($a) { /**/ } ',
                      'static public function getCalledClassWithThis( ) { /**/ } ',
                     );

?>
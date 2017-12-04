<?php

$expected     = array('function bothpropertyandmethod( ) { /**/ } ',
                      'function property( ) { /**/ } ',
                      'function method( ) { /**/ } ',
                      'static public function nothingButStatic( ) { /**/ } ',
                      'public static function nothingButStatic2( ) { /**/ } ',
                     );

$expected_not = array('function nothing( ) { /**/ } ',
                     );

?>
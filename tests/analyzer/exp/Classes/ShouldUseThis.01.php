<?php

$expected     = array('function nothing( ) { /**/ } ',
                     );

$expected_not = array('function property( ) { /**/ } ',
                      'function method( ) { /**/ } ',
                      'function bothpropertyandmethod( ) { /**/ } ',
                      'static function nothingButStatic( ) { /**/ } ',
                      'static function nothingButStatic2( ) { /**/ } ',
                     );

?>
<?php

$expected     = array('static public function staticButEmpty( ) { /**/ } ',
                      'static function staticConstant( ) { /**/ } ',
                     );

$expected_not = array('static function staticProperty( ) { /**/ } ',
                      'static function staticMethod( ) { /**/ } ',
                      'static function bothpropertyandmethod( ) { /**/ } ',
                     );

?>
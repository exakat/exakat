<?php

$expected     = array('static public function useThisButEmpty( ) { /**/ } ',
                      'static function useThisConstant( ) { /**/ } ',
                     );

$expected_not = array('static function useThisProperty( ) { /**/ }',
                      'static function useThisMethod( ) { /**/ }',
                      'static function bothpropertyandmethod( ) { /**/ }',
                     );

?>
<?php

$expected     = array('static function actuallyNonStaticMethod( ) { /**/ } ',
                      'static function actuallyNonStaticMethodInTrait( ) { /**/ } ',
                      'static function actuallyNonStaticMethod( ) { /**/ } ',
                      'static function actuallyNonStaticMethodInTrait( ) { /**/ } ',
);

$expected_not = array('static function staticMethod( ) { /**/ } ',
                      'static function staticMethodInTrait( ) { /**/ } ',
                      'static function realFunctionNoThis( ) { /**/ } ',
                      'static function realFunction( ) { /**/ } ',
                      );

?>
<?php

$expected     = array('static function staticMethodUsingThis( ) { /**/ } ',
                      'static function staticTraitMethodUsingThis( ) { /**/ } ',
                     );

$expected_not = array('static function staticMethod( ) { /**/ } ',
                      'static function staticMethodInTrait( ) { /**/ } ',
                      'static function realFunctionNoThis( ) { /**/ } ',
                      'static function realFunction( ) { /**/ } ',
                      'static function nonStaticMethod( ) { /**/ } ',
                      'static function nonStaticMethodInTrait( ) { /**/ } ',
                     );

?>
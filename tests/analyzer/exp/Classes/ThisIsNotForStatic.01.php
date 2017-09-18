<?php

$expected     = array('static function nonStaticMethod( ) { /**/ } ',
                      'static function nonStaticMethodInTrait( ) { /**/ } ',
                     );

$expected_not = array('static function staticMethod( ) { /**/ } ',
                      'static function staticMethodInTrait( ) { /**/ } ',
                      'static function realFunctionNoThis( ) { /**/ } ',
                      'static function realFunction( ) { /**/ } ',
                     );

?>
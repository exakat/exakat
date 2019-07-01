<?php

$expected     = array('static function aStatic( ) { /**/ } ',
                      'static function aSelf( ) { /**/ } ',
                      'static function aZUse( ) { /**/ } ',
                      'static function aX( ) { /**/ } ',
                      'static function aNsname( ) { /**/ } ',
                     );

$expected_not = array('static function aY( ) { /**/ } ',
                     );

?>
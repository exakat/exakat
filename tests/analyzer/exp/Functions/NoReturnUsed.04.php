<?php

$expected     = array('static function fooReturn($a) { /**/ } ',
                      'static function fooNull($a) { /**/ } ',
                      'static function fooVoidInt($a) { /**/ } ',
                     );

$expected_not = array('static function fooVoidVoid($a) { /**/ } ',
                      'static function fooVoid($a) { /**/ } ',
                     );

?>
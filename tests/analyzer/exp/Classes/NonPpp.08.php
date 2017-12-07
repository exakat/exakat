<?php

$expected     = array('function x( ) ;',
                      'static function x2( ) ;',
                     );

$expected_not = array('public function x( ) { /**/ } ',
                      'public static function x2( ) { /**/ } ',
                      'private function x( ) { /**/ } ',
                      'private static function x2( ) { /**/ } ',
                     );

?>
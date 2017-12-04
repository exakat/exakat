<?php

$expected     = array('function b( ) { /**/ } ',
                      'public function apublicButSBProtected( ) { /**/ } ',
                     );

$expected_not = array('public static function aspublicButReally( ) { /**/ } ',
                      'public static function aspublicButReally2( ) { /**/ } ',
                      'apublicButReally2',
                      'apublicButReally',
                     );

?>
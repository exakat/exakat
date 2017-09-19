<?php

$expected     = array('public static function aspublicButSBProtectedSelf( ) { /**/ } ', 
                      'public static function aspublicButSBProtectedStatic( ) { /**/ } ', 
                      'public static function aspublicButSBProtectedFull( ) { /**/ } ', 
                      'function b( ) { /**/ } ', 
                      'public function apublicButSBProtected( ) { /**/ } ',
                      );

$expected_not = array('public static function aspublicButReally( ) { /**/ } ', 
                      'public static function aspublicButReally2( ) { /**/ } ', 
                      'apublicButReally2', 
                      'apublicButReally', 
                     );

?>
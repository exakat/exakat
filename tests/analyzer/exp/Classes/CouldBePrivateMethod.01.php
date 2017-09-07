<?php

$expected     = array('public static function aspublicButSBPrivateSelf( ) { /**/ } ',
                      'public static function aspublicButSBPrivateStatic( ) { /**/ } ',
                      'public static function aspublicButSBPrivateFull( ) { /**/ } ',
                      'public function apublicButSBPrivate( ) { /**/ } ',
                      'protected function aprotected( ) { /**/ } ',
                      'protected static function asprotected( ) { /**/ } ',
                      'function b( ) { /**/ } ',
                     );

$expected_not = array('public function apublicButReally() { /**/ } ', 
                      'public function apublicButReally2() { /**/ } ',
                     );

?>
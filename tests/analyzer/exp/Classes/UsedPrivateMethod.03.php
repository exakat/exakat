<?php

$expected     = array('private static function privateStaticM7( ) { /**/ } ',
                      'private static function privateStaticM6( ) { /**/ } ',
                      'private static function privateStaticM5( ) { /**/ } ',
                      'private static function privateStaticM4( ) { /**/ } ',
                      'private function privateM( ) { /**/ } ',
                      'private static function privateStaticM3( ) { /**/ } ',
                     );

$expected_not = array('private static function privateStaticM72( ) { /**/ } ',
                      'private static function privateStaticM62( ) { /**/ } ',
                      'private static function privateStaticM52( ) { /**/ } ',
                      'private static function privateStaticM42( ) { /**/ } ',
                      'private function privateM2( ) { /**/ } ',
                      'private static function privateStaticM32( ) { /**/ } ',
                     );

?>
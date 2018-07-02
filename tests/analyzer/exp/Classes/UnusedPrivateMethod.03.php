<?php

$expected     = array('private static function privateStaticM52( ) { /**/ } ',
                      'private static function privateStaticM42( ) { /**/ } ',
                      'private static function privateStaticM32( ) { /**/ } ',
                      'private function privateM2( ) { /**/ } ',
                      'private static function privateStaticM72( ) { /**/ } ',
                      'private static function privateStaticM62( ) { /**/ } ',
                     );

$expected_not = array('private static function privateStaticM72( ) { /**/ } ',
                      'private static function privateStaticM62( ) { /**/ } ',
                      'private static function privateStaticM52( ) { /**/ } ',
                      'private static function privateStaticM42( ) { /**/ } ',
                      'private function privateM2( ) { /**/ } ',
                      'private static function privateStaticM32( ) { /**/ } ',
                     );

?>
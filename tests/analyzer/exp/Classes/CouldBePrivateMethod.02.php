<?php

$expected     = array('public function apublicButSBPrivate( ) { /**/ } ',
                      'protected function aprotected( ) { /**/ } ',
                      'protected static function asprotected( ) { /**/ } ',
                      'function b( ) { /**/ } ',
                     );

$expected_not = array('public function apublicButReally() { /**/ } ', 
                      'public function apublicButReally2() { /**/ } ',
                      'public function __construct() { /**/ } ',
                      'public function __clone() { /**/ } ',
                      'public function __toString() { /**/ } ',
                     );

?>
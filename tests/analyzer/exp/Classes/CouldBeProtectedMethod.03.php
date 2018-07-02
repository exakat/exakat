<?php

$expected     = array('public function apublicButSBProtected( ) { /**/ } ',
                      'public function apublicButSBProtected2( ) { /**/ } ',
                      'public function apublicButSBProtected3( ) { /**/ } ',
                      'function b( ) { /**/ } ',
                      'public function unused( ) { /**/ } ',
                     );

$expected_not = array('public function apublicButReally( ) { /**/ } ',
                      'public function apublicButReally2( ) { /**/ } ',
                      'public function apublicButReally3( ) { /**/ } ',
                     );

?>
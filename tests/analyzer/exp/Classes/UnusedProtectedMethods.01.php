<?php

$expected     = array('protected function unused( ) { /**/ } ',
                     );

$expected_not = array('protected function ma31( ) { /**/ } ',
                      'protected function ma2( ) { /**/ } ',
                      'protected function ma32( ) { /**/ } ',
                      'private function pma1( ) { /**/ } ',
                      'public function puma1( ) { /**/ } ',
                      'protected function ma2( ) { /**/ } ',
                      'protected function ma1( ) { /**/ } ',
                      'protected function ma21($b) { /**/ } ',
                      'protected function ma22($b) { /**/ } ',
                      'protected function ma221($b) { /**/ } ',
                      'protected function ma232($b) { /**/ } ',
                     );

?>
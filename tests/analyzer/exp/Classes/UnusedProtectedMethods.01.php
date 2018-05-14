<?php

$expected     = array('protected function unused( ) { /**/ } ',
                      'protected function ma2( ) { /**/ } ',
                      'protected function ma1( ) { /**/ } ',
                     );

$expected_not = array('protected function ma31( ) { /**/ } ',
                      'protected function ma2( ) { /**/ } ',
                      'protected function ma32( ) { /**/ } ',
                      'private function pma1( ) { /**/ } ',
                      'public function puma1( ) { /**/ } ',
                     );

?>
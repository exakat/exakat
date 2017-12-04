<?php

$expected     = array('protected function ma31( ) { /**/ } ',
                      'protected function ma2( ) { /**/ } ',
                      'protected function ma32( ) { /**/ } ',
                     );

$expected_not = array('protected function unused( ) { /**/ } ',
                      'protected function ma321( ) { /**/ } ',
                      'private function pma1( ) { /**/ } ',
                      'public function puma1( ) { /**/ } ',
                     );

?>
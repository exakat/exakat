<?php

$expected     = array('protected function Ma31( ) { /**/ } ',
                      'protected function Ma2( ) { /**/ } ',
                      'protected function Ma32( ) { /**/ } ',
                      'protected function Ma2($b) { /**/ } ',
                     );

$expected_not = array('protected function unused( ) { /**/ } ',
                      'protected function Ma321( ) { /**/ } ',
                      'private function pMa1( ) { /**/ } ',
                      'public function puMa1( ) { /**/ } ',
                     );

?>
<?php

$expected     = array('protected function __clone( ) { /**/ } ',
                      'protected function Ma2( ) { /**/ } ',
                      'protected function __get($a) { /**/ } ',
                     );

$expected_not = array('protected function unused( ) { /**/ } ',
                      'protected function Ma321( ) { /**/ } ',
                      'private function pMa1( ) { /**/ } ',
                      'public function puMa1( ) { /**/ } ',
                     );

?>
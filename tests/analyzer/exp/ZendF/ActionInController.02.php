<?php

$expected     = array('public function myZendAuthAction( ) { /**/ } ',
                      'protected function protectedAction( ) { /**/ } ',
                      'private function privateAction( ) { /**/ } ',
                     );

$expected_not = array('function noVisibilityAction( ) { /**/ } ',
                      'public function myAction( ) { /**/ } ',
                      'function otherMethod() { /**/ } ',
                     );

?>
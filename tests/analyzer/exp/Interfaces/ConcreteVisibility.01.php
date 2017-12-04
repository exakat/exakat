<?php

$expected     = array('private function interfaceMethod1( ) { /**/ } ',
                      'protected function interfaceMethod2( ) { /**/ } ',
                     );

$expected_not = array('private function interfaceMethod3( ) { /**/ } ',
                      'function interfaceMethod4( ) { /**/ } ',
                      'function classMethod() {}',
                      'function notMethod() {}',
                      'private function traitMethod1() {}',
                     );

?>
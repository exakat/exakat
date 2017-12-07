<?php

$expected     = array('function functionWithoutReturn($x) { /**/ } ',
                      'private function methodWithoutReturn( ) { /**/ } ',
                     );

$expected_not = array('function functionWithReturn($x) { /**/ } ',
                      'private function methodWithReturn( ) { /**/ } ',
                     );

?>
<?php

$expected     = array('private function methodWithoutReturn( ) { /**/ } ',
                      'function functionWithoutReturn($x) { /**/ } ',
                     );

$expected_not = array('private function methodWithoutReturnButVoid( ) : void { /**/ } ',
                      'function functionWithoutReturnButVoid($x) : void { /**/ } ',
                     );

?>
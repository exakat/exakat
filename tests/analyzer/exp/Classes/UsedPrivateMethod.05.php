<?php

$expected     = array('protected function foo($x3) { /**/ } ',
                      'protected static function foo2($x3) { /**/ } ',
                     );

$expected_not = array('protected function foo($x1) { /**/ } ',
                      'protected function foo($x2) { /**/ } ',
                      'protected static function foo2($x1) { /**/ } ',
                      'protected static function foo2($x2) { /**/ } ',
                     );

?>
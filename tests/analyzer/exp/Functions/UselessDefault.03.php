<?php

$expected     = array('static function foo3($a, $b = 1) { /**/ } ',
                     );

$expected_not = array('static function foo1($a, $b = 1) { /**/ } ',
                      'static function foo2($a, $b = 1) { /**/ } ',
                      'static function foo3b($a, $b = 1) { /**/ } ',
                     );

?>
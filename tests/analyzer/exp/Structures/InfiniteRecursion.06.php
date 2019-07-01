<?php

$expected     = array('static function foobar( ) { /**/ } ',
                      'static function bar2(x $a) { /**/ } ',
                     );

$expected_not = array('static function bar($a) { /**/ } ',
                      'static function foo($a) { /**/ } ',
                     );

?>
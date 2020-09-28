<?php

$expected     = array('function test($a = [ ], $b) { /**/ } ',
                      'function test2($a = null, $b) { /**/ } ',
                     );

$expected_not = array('function test3(Foo $a = null, $b) { /**/ } ',
                      'function test4(Foo $a = null) { /**/ } ',
                     );

?>
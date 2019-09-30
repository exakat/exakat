<?php

$expected     = array('function foo3(float $a = STRING) { /**/ } ',
                     );

$expected_not = array('function foo($a) { /**/ } ',
                      'function foo1($a = 1) { /**/ } ',
                      'function foo2(int $a) { /**/ } ',
                     );

?>
<?php

$expected     = array('function foo3(float $a = STRING ?: 2.2) { /**/ } ',
                      'function foo2(float $a = (STRING == 1 ? \'a\' : 2.2)) { /**/ } ',
                      'function foo1(float $a = STRING == 1 ? \'a\' : 2.2) { /**/ } ',
                     );

$expected_not = array('function foo4(float $a = FLOAT ?: 2.2) { /**/ } ',
                     );

?>
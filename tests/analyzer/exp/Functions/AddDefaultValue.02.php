<?php

$expected     = array('function foo1($x) { /**/ } ',
                      'function foo6($x, $y, $z) { /**/ } ',
                      'function foo7($x) { /**/ } ',
                      'function foo8($x) { /**/ } ',
                     );

$expected_not = array('function foo2($x = null) { /**/ } ',
                      'function foo3($x = 1) { /**/ } ',
                      'function foo4($x) { /**/ } ',
                      'function foo5($x) { /**/ } ',
                     );

?>
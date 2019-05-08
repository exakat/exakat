<?php

$expected     = array('function foo3($a, $b = 1) { /**/ } ',
                     );

$expected_not = array('function foo1($a, $b = 1) { /**/ } ',
                      'function foo2($a, $b = 1) { /**/ } ',
                      'function foo3b($a, $b = 1) { /**/ } ',
                     );

?>
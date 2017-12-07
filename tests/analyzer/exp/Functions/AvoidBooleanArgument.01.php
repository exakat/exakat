<?php

$expected     = array('function foo3($a, $b = \\true) { /**/ } ',
                      'function foo2($a = false, $b = 1) { /**/ } ',
                      'function foo1($a = true, $b = 1) { /**/ } ',
                     );

$expected_not = array('function foo4($a = true, boolean $b) { /**/ } ',
                     );

?>
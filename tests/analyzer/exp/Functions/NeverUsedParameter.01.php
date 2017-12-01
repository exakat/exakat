<?php

$expected     = array('function foo3($a, $b = 1, $c = 2, $d = 4) { /**/ } ',
                      'function foo($a, $b = 1, $c = 2) { /**/ } ',
                     );

$expected_not = array('function foo4($a, $b, $c) { /**/ } ',
                      'function foo2($a, $b = 1, $c = 2) { /**/ } ',
                     );

?>
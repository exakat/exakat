<?php

$expected     = array('function foo3($a, $b, &$c) { /**/ } ',
                      'function foo($a, &$b, &$c) { /**/ } ',
                     );

$expected_not = array('function foo2($a, $b, $c) { /**/ } ',
                      'function foo4($a, &$b, $c) { /**/ } ',
                     );

?>
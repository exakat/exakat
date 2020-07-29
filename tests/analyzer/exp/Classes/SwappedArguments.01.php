<?php

$expected     = array('function foo($b, $a) { /**/ } ',
                      'function foo5($a, $d, $c, $b) { /**/ } ',
                     );

$expected_not = array('function foo2($a, $b) { /**/ } ',
                      'function foo3($d, $c) { /**/ } ',
                     );

?>
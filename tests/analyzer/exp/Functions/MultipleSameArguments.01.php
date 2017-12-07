<?php

$expected     = array('function x1($a, $b, $a, $c) { /**/ } ',
                      'function x2($a = 2, $b, $a, $c) { /**/ } ',
                      'function x3($a = 2, $b, $a = 3, $c) { /**/ } ',
                      'function x4($a, $b, $a = 3, $c) { /**/ } ',
                      'function x5(Stdclass $a, $b, $a, $c) { /**/ } ',
                      'function x6($a, $b, Stdclass $a, $c) { /**/ } ',
                      'function x7($a, $b, &$a, $c) { /**/ } ',
                      'function x8(&$a, $b, &$a, $c) { /**/ } ',
                      'function x9($a, $a, $a, $c) { /**/ } ',
                     );

$expected_not = array('function xok($a, $b, $c, $d) { /**/ } ',
                     );

?>
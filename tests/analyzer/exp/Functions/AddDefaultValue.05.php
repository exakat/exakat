<?php

$expected     = array('function foo4($a = 2, $fields) { /**/ } ',
                      'function foo5($a = 2, $fields) { /**/ } ',
                      'function foo6($a = 2, $fields) { /**/ } ',
                     );

$expected_not = array('function foo1(&$x) { /**/ } ',
                      'function foo2(...$x) { /**/ } ',
                      'function foo3($a = 2, $fields) { /**/ } ',
                     );

?>
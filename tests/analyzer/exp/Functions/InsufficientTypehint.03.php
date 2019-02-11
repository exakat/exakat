<?php

$expected     = array('function foo2a(i $a) { /**/ } ',
                     );

$expected_not = array('function foo2(i $a) { /**/ } ',
                      'function foo($a) { /**/ } ',
                      'function foo1(i $a) { /**/ } ',
                     );

?>
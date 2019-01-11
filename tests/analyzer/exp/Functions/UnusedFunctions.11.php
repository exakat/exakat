<?php

$expected     = array('function foo2($A) { /**/ } ',
                     );

$expected_not = array('function foo($A) { /**/ } ',
                      'function foo3($A) { /**/ } ',
                      'function foo($B) { /**/ } ',
                      'function foo($C) { /**/ } ',
                     );

?>
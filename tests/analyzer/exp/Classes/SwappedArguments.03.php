<?php

$expected     = array('function foo2b($b, $a) { /**/ } ',
                      'function foo3b($b, $a, $c) { /**/ } ',
                      'function foo3c($a, $c, $b) { /**/ } ',
                     );

$expected_not = array('function foo2($a, $b) { /**/ } ',
                      'function foo0( ) { /**/ } ',
                     );

?>
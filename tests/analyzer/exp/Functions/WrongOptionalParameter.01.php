<?php

$expected     = array('function optionalKO23b($x = 1, $y, $z, $a = 3) { /**/ } ',
                      'function optionalKO24($x = 1, $y, $z = 2, $a) { /**/ } ',
                      'function optionalKO34($x = 1, $y = 2, $z, $a) { /**/ } ',
                      'function optionalKO4($x, $y, $z = 1, $a) { /**/ } ',
                      'function optionalKO134($x, $y = 1, $z, $a) { /**/ } ',
                      'function optionalKO234($x = 1, $y, $z, $a) { /**/ } ',
                      'function optionalKO23($x = 1, $y, $z) { /**/ } ',
                      'function optionalKO3($x = 1, $y = 2, $z) { /**/ } ',
                      'function optionalKO2($x = 1, $y) { /**/ } ',
                     );

$expected_not = array('function optionalOK5($x, $y, $z = 3) { /**/ } ',
                     );

?>
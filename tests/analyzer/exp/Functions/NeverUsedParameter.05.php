<?php

$expected     = array('function hoo($a, $b = 2, $c = 4) { /**/ } ', 
                      'function foo($a, $b = 2, $c = 3) { /**/ } ',
                      'function ioo($a, $b = 2, $c = 6) { /**/ } ',
                     );

$expected_not = array('function goo($a, $b = 2, $c = 5) { /**/ } ',
                     );

?>
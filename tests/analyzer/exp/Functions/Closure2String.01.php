<?php

$expected     = array('function ($o) { /**/ } ',
                      'function ($c, $a) { /**/ } ',
                      'function ($f) { /**/ } ',
                     );

$expected_not = array('function ($n) { /**/ } ',
                      'function foo($n) { /**/ } ',
                     );

?>
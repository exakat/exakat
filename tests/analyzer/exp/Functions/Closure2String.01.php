<?php

$expected     = array('function ($c, $a) { /**/ } ',
                      'function ($o) { /**/ } ',
                      'function ($f) { /**/ } ',
                     );

$expected_not = array('function ($n) { /**/ } ',
                      'function foo($n) { /**/ } ',
                     );

?>
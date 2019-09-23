<?php

$expected     = array('function ($o) { /**/ } ',
                      'function ($f) { /**/ } ',
                     );

$expected_not = array('function ($c, $a) { /**/ } ',
                      'function ($n) { /**/ } ',
                      'function foo($n) { /**/ } ',
                     );

?>
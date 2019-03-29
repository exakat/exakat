<?php

$expected     = array('function ($f) { /**/ } ',
                     );

$expected_not = array('function ($c, $a) { /**/ } ',
                      'function ($o) { /**/ } ',
                      'function ($n) { /**/ } ',
                      'function foo($n) { /**/ } ',
                     );

?>
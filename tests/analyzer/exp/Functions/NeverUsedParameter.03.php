<?php

$expected     = array('function ($a, $b = 1, $c = 2, $d = 4) { /**/ } ',
                      'function ($a, $b = 1, $c = 2) { /**/ } ',
                     );

$expected_not = array('function ($a, $b, $c) { /**/ } ',
                      'function ($a, $b = 1, $c = 2) { /**/ } ',
                     );

?>
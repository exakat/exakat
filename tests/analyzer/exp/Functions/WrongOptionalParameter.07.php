<?php

$expected     = array('function ($b, $c, $d = null, $e) { /**/ } ',
                      'function ($b = 2, $c) { /**/ } ',
                     );

$expected_not = array('function ($b, $c) { /**/ } ',
                      'function ($b = 2, $c) {  $e++;  } ',
                      'function ($b) { /**/ } ',
                      'function ($b = 1) { /**/ } ',
                     );

?>
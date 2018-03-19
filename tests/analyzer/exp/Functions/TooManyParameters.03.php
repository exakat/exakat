<?php

$expected     = array('function ($a1, $a2, $a3, $a4, $a5, $a6, $a7, $a8, $a9) { /**/ } ',
                      'function ($a1, $a2, $a3, $a4, $a5, $a6, $a7, $a8, $a9, $a10) { /**/ } ',
                     );

$expected_not = array('function ($a1, $a2, $a3) { /**/ } ',
                      'function ($a1, $a2, $a3, $a4) { /**/ } ',
                      'function ($a1, $a2, $a3, $a4, $a5) { /**/ } ',
                      'function ($a1, $a2, $a3, $a4, $a5, $a6) { /**/ } ',
                      'function ($a1, $a2, $a3, $a4, $a5, $a6, $a7) { /**/ } ',
                      'function ($a1, $a2, $a3, $a4, $a5, $a6, $a7, $a8) { /**/ } ',
                     );

?>
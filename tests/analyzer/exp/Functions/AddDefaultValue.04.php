<?php

$expected     = array('function ($x) { /**/ } ',
                      'function ($x, $y, $z) { /**/ } ',
                      'function ($x) { /**/ } ',
                      'function ($x) { /**/ } ',
                     );

$expected_not = array('function ($x = null) { /**/ } ',
                      'function ($x = 1) { /**/ } ',
                      'function ($x) { /**/ } ',
                      'function ($x) { /**/ } ',
                     );

?>
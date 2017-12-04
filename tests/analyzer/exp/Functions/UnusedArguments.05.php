<?php

$expected     = array('function A2($a, $b) { /**/ } ',
                      'function at2($a, $b) { /**/ } ',
                      'function a2($a, $b) { /**/ } ',
                     );

$expected_not = array('function ffoo12($a, $b) { /**/ } ',
                      'function ffoo1($a, $b) { /**/ } ',
                      'function ffoo12($a1, $b) { /**/ } ',
                      'function ffoo1($a1, $b) { /**/ } ',
                      'function at2($a, $b) { /**/ } ',
                      'function A1($a, $b) { /**/ } ',
                      'function at1($a, $b) { /**/ } ',
                      'function a1($a, $b) { /**/ } ',
                     );

?>
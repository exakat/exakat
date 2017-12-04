<?php

$expected     = array('foreach($c3 as &$d4) { /**/ } ',
                      'foreach($c3 as &$d3) { /**/ } ',
                     );

$expected_not = array('foreach($a as &$b) { /**/ } ',
                      'foreach($c as &$d) { /**/ } ',
                      'foreach($c2 as &$d2) { /**/ } ',
                      'foreach($e as &$f) { /**/ } ',
                      'foreach($g as &$h) { /**/ } ',
                     );

?>
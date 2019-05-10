<?php

$expected     = array('foreach($c3 as &$d4) { /**/ } ',
                      'foreach($c3 as &$d3) { /**/ } ',
                      'foreach($g as &$h) { /**/ } ',
                     );

$expected_not = array('foreach($c as &$d) { /**/ } ',
                      'foreach($c2 as &$d2) { /**/ } ',
                      'foreach($e as &$f) { /**/ } ',
                      'foreach($g as &$h) { /**/ } ',
                      'foreach($a as &$b) { /**/ } ',
                     );

?>
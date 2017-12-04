<?php

$expected     = array('foreach($c as $d) { /**/ } ',
                      'foreach($c2 as $d2) { /**/ } ',
                     );

$expected_not = array('foreach($c3 as $d3) { /**/ } ',
                      'foreach($a as $b) { /**/ } ',
                      'do { /**/ }  while (1)',
                     );

?>
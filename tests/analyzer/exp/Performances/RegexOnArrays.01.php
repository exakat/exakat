<?php

$expected     = array('foreach($A as $B) { /**/ } ',
                      'foreach($a as $b) { /**/ } ',
                     );

$expected_not = array('foreach($a1 as $b1) { /**/ } ',
                      'foreach($a2 as $b2) { /**/ } ',
                     );

?>
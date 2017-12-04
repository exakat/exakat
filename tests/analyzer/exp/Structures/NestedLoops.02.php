<?php

$expected     = array('foreach($a1 as $b1) { /**/ } ',
                      'foreach($a3 as $b3) { /**/ } ',
                      'foreach($a2 as $b2) { /**/ } ',
                      'foreach($a4 as $b4) { /**/ } ',
                     );

$expected_not = array('foreach($a5 as $b5) { /**/ } ',
                     );

?>
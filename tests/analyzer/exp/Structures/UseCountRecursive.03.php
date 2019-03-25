<?php

$expected     = array('foreach($a as $c => $b1) { /**/ } ',
                      'foreach($a as $c => $b4) { /**/ } ',
                     );

$expected_not = array('foreach($a as $c => $b2) { /**/ } ',
                      'foreach($a as $c => $b3) { /**/ } ',
                      'foreach($a as $c => $b5) { /**/ } ',
                     );

?>
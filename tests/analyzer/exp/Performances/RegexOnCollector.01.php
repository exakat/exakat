<?php

$expected     = array('foreach($a as $b1) { /**/ } ',
                      'foreach($a as $k => $b2) { /**/ } ',
                      'foreach($a as $k3 => $b3) { /**/ } ',
                      'foreach($a as $k4 => $b4) { /**/ } ',
                      'foreach($a as $k5 => $b5) { /**/ } ',
                     );

$expected_not = array('',
                      'foreach($a as $k6 => $b6) { /**/ } ',
                     );

?>
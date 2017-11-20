<?php

$expected     = array('foreach($a as $b6) { /**/ } ', 
                      'foreach($a as $b2) { /**/ } ',
                      'foreach($a as $b5) { /**/ } ',
                     );

$expected_not = array('foreach($a as $b1) { /**/ } ', 
                      'foreach($a as $b3) { /**/ } ',
                      'foreach($a as $b4) { /**/ } ',
                      'foreach($a as list($b7, $c7)) { /**/ } ',
                     );

?>
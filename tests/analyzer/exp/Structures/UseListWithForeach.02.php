<?php

$expected     = array('foreach($a as $k => $b2) { /**/ } ',
                      'foreach($a as $k => $b6) { /**/ } ', 
                      'foreach($a as $k => $b5) { /**/ } ',
                     );

$expected_not = array('foreach($a as $k => $b1) { /**/ } ', 
                      'foreach($a as $k => $b3) { /**/ } ',
                      'foreach($a as $k => $b4) { /**/ } ',
                      'foreach($a as $k => list($b7, $c7)) { /**/ } ',
                     );

?>
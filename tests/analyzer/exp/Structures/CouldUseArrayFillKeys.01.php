<?php

$expected     = array('foreach($a as $b2 => $c2) { /**/ } ', 
                      'foreach($a as $b3 => $c3) { /**/ } ', 
                      'foreach($a as $d => $e) { /**/ } ',
                      'foreach($a as $b) { /**/ } ',
                     );

$expected_not = array('foreach($a as $d => $e2) { /**/ } ', 
                      'foreach($a as $b2 => $c2) { /**/ } ', 
                     );

?>
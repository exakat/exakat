<?php

$expected     = array('foreach($a as &$b3) { /**/ } ',
                      'foreach($a as &$o1) { /**/ } ',
                     );

$expected_not = array('foreach($a as &$b) { /**/ } ',
                      'foreach($a as &$b2) { /**/ } ',
                      'foreach($a as &$o) { /**/ } ',
                     );

?>
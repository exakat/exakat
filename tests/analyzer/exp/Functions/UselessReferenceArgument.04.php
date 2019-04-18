<?php

$expected     = array('foreach($a as &$b4) { /**/ } ',
                      'foreach($a as $k => &$b5) { /**/ } ',
                      'foreach($a as &$b2) { /**/ } ',
                      'foreach($a as $k => &$b3) { /**/ } ',
                     );

$expected_not = array('foreach($a as $b) { /**/ } ',
                      'foreach($a as &$b6) { /**/ } ',
                     );

?>
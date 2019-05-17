<?php

$expected     = array('foreach($i as &$j) { /**/ } ',
                      'foreach($c as &$d) { /**/ } ',
                     );

$expected_not = array('foreach($a as &$b) { /**/ } ',
                      'foreach($f as &$g) { /**/ } ',
                     );

?>
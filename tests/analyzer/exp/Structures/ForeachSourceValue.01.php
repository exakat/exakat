<?php

$expected     = array('foreach($c as $b => $c) { /**/ } ',
                      'foreach($d as $d => $d) { /**/ } ',
                      'foreach($b as $b => $c) { /**/ } ',
                      'foreach($a as $a) { /**/ } ',
                     );

$expected_not = array('foreach($a1 as $a) { /**/ } ',
                     );

?>
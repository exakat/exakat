<?php

$expected     = array('foreach($s2 as $k2 => &$a2) { /**/ } ',
                     );

$expected_not = array('foreach($s1 as $k1 => &$a1) { /**/ } ',
                      'foreach($a2 as $b => $c2) { /**/ } ',
                      'foreach($a1 as $b => &$c) { /**/ } ',
                     );

?>
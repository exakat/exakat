<?php

$expected     = array('foreach($a as $c => $a) { /**/ } ',
                      'foreach($a as $a) { /**/ } ',
                      'foreach($a as &$a) { /**/ } ',
                      'foreach($a as [\'a\' => $a]) { /**/ } ',
                      'foreach($a as $a => $ac) { /**/ } ',
                     );

$expected_not = array('foreach($a as $b) { /**/ } ',
                     );

?>
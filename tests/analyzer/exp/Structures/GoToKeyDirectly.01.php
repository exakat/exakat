<?php

$expected     = array('foreach($a1 as $k => $v) { /**/ } ',
                     );

$expected_not = array('foreach($A1 as $k => $v) { /**/ } ',
                      'foreach($A2 as $k => $v) { /**/ } ',
                     );

?>
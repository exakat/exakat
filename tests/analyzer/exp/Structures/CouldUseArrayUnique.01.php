<?php

$expected     = array('foreach($a1 as $b) { /**/ } ',
                      'foreach($a2 as $k => $b) { /**/ } ',
                     );

$expected_not = array('foreach($a3 as $k => $b) { /**/ } ',
                     );

?>
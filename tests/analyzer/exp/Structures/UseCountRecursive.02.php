<?php

$expected     = array('foreach($switch as $key => $val4) { /**/ } ',
                     );

$expected_not = array('foreach($switch as $key => $val) { /**/ } ',
                      'foreach($switch as $key => $val2) { /**/ } ',
                      'foreach($switch as $key => $val3) { /**/ } ',
                     );

?>
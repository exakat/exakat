<?php

$expected     = array('chmod($f, 0777)', 
                      'chmod($f, 511)', 
                      'chmod($f, 0b111111111)', 
                      'chmod($f, -1)',
                     );

$expected_not = array('chmod($f, 0)',
                      'chmod($f, 0777)',
                      'chmod($f, 777)',
                     );

?>
<?php

$expected     = array('isset($a[9], $a[9][8])', 
                      'isset($a[7][8], $a[7])', 
                      'isset($a, $a[3])', 
                      'isset($a[4], $a)', 
                      'isset($a[2][3]) || isset($a[2][3][1])', 
                      'isset($a[2]) || isset($a[2][1])', 
                      'isset($a) || isset($a[1])',
                     );

$expected_not = array('isset($a) || isset($b[1])',
                     );

?>
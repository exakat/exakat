<?php

$expected     = array('isset($a, $a[3]',
                      'isset($a[4], $a',
                      'isset($a[6], $a[5]',
                      'isset($a[7][8], $a[7]',
                      'isset($a[9], $a[9][8]',
                      '',
                      'isset($a) || isset($a[1]',
                      'isset($a[2]) || isset($a[2][1]',
                      'isset($a[2][3]) || isset($a[2][3][1]',
                      'isset($a[2][3]) || isset($a[2][3][1][7]',
                     );

$expected_not = array('isset($a) || isset($b[1])',
                     );

?>
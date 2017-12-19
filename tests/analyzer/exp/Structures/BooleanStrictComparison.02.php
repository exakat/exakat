<?php

$expected     = array('in_array($array, $default)',
                      'in_array($array, $zero, 0)',
                      'in_array($array, $false, false)',
                     );

$expected_not = array('in_array($array, $true, true)',
                      'in_array($array, $zero, $x)',
                     );

?>
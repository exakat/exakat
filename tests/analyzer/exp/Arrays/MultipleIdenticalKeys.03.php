<?php

$expected     = array('array(1 => 1, true => 2)',
                      'array(1 => 1, \\true => 2)',
                      'array(0 => 1, \\null => 2)',
                     );

$expected_not = array('array(1 => 1, \\truth => 2)',
                      'array(0 => 1, \'null\' => 2)',
                     );

?>
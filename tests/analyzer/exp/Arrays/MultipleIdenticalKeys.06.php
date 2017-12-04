<?php

$expected     = array('array(\'0xB7\' => 1, \'0xB8\' => 1, \'0xB8\' => 1)',
                      'array(0xB7 => 1, 0xB8 => 1, 0xB7 => 1,  )',
                     );

$expected_not = array('array(\'0xB7\' => 1, \'0xB8\' => 1, \'0xB9\' => 1)',
                      'array(0xB7 => 1, 0xB8 => 1, 0xB9 => 1,  )',
                     );

?>
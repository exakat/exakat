<?php

$expected     = array('strpos($a1, \'c\')',
                      'stripos($a2, \'c\')',
                     );

$expected_not = array('strripos($a3, \'c\')',
                      'strripos($a4, \'c\')',
                      'strrpos($a5, \'c\')',
                     );

?>
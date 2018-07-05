<?php

$expected     = array('-$b', 
                      '$b++', 
                      'isset($b)', 
                      'empty($b)', 
                      '$b ?? \'c\'',
                     );

$expected_not = array('new A( )',
                     );

?>
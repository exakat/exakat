<?php

$expected     = array('include_once __DIR__ . \'/INC/INCLUDE.php\'', 
                      'include_once __DIR__ . \'/inc/INCLUDE.php\'', 
                      'include_once __DIR__ . \'/INC/include.php\'', 
                      'include_once __DIR__ . \'INCLUDE.php\'', 
                      'include (__DIR__ . \'/../INCLUDE.php\')', 
                      'include_once __DIR__ . \'/INCLUDE.php\'',
                     );

$expected_not = array('include (__DIR__ . \'/../include.php\')', 
                     );

?>
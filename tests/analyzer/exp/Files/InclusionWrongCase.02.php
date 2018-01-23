<?php

$expected     = array('include_once (\'inc/INCLUDE.php\')', 
                      'include_once (\'INC/include.php\')', 
                      'REQUIRE \'INCLUDE.PHP\'', 
                      'include_once (\'/inc/INCLUDE.php\')', 
                      'include_once (\'/INC/include.php\')', 
                      'require \'include.php\'', 
                      'require_once \'include2.PHP\'',
                     );

$expected_not = array('include (\'/INC/INCLUDE.php\')', 
                      'include (\'INCLUDE2.php\')', 
                      'include(\'INC/NONEXISTENT.php\')',
                      'include(\'NONEXISTENT2.php\')',
                     );

?>
<?php

$expected     = array('include (\'/inc/INCLUDE.php\')', 
                      'include (\'/INC/include.php\')', 
                      'include (\'inc/INCLUDE.php\')', 
                      'include (\'INC/include.php\')', 
                      'require_once \'INCLUDE2.php\'', 
                      'require_once \'include2.PHP\'', 
                      'require \'include.PHP\'',
                     );

$expected_not = array('include (\'/inc/include.php\')', 
                      'include (\'include2.php\')', 
                      'include(\'inc/nonexistent.php\')',
                      'include(\'nonexistent2.php\')',
                     );

?>
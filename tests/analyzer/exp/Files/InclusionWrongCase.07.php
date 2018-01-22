<?php

$expected     = array('include (dirname(__FILE__) . \'/../INCLUDE.php\')',
                      'include_once dirname(__FILE__) . \'/INCLUDE.php\'',
                     );

$expected_not = array('include (dirname(__FILE__) . \'/../include.php\')',
                      'include (dirname(__FILE__) . \'/../inexistant.php\')',
                     );

?>
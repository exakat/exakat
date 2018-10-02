<?php

$expected     = array('include \'include.PHP\'',
                      'include (MY_INCLUDE)',
                      'include (dirname(__FILE__)) . \'/include.phP\'',
                      'include dirname(__FILE__) . \'/include.PHP\'',
                      'include (dirname(__FILE__)) . \'/include.pHP\'',
                     );

$expected_not = array('include \'include.php\'',
                      'include \'\'',
                     );

?>
<?php

$expected     = array('include_once (\'./INC/include.php\')',
                      'include_once (\'./inc/INCLUDE.php\')',
                      'include_once (\'./INCLUDE.php\')',
                     );

$expected_not = array('include_once (\'./inc/include.php\')',
                      'include_once (\'./include.php\')',
                     );

?>
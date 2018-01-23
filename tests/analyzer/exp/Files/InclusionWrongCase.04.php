<?php

$expected     = array('include_once (\'../INCLUDE.php\')',
                     );

$expected_not = array('include_once(\'../include.php\')',
                      'include_once(\'../inexistant.php \')',
                     );

?>
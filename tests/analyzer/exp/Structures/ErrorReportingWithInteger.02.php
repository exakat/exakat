<?php

$expected     = array('ini_set(\'error_reporting\', 1)',
                     );

$expected_not = array('ini_set(\'error_reporting\', E_ALL)',
                      'ini_set(\'others\', 3)',
                     );

?>
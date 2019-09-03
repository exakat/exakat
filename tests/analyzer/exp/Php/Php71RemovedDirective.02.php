<?php

$expected     = array('INI_set(\'session.hash_function\', 1)',
                     );

$expected_not = array('ini_set(\'session.HASH_FUNCTION\', 1)',
                     );

?>
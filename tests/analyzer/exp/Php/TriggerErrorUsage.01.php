<?php

$expected     = array('trigger_error(\'this is a mistake\')',
                      'USER_ERROR(\'this is another mistake\', E_USER_NOTICE)',
                     );

$expected_not = array('trigger_error(\'this is a method\')',
                     );

?>
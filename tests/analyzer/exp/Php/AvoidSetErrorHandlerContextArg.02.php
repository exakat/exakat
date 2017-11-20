<?php

$expected     = array('set_error_handler(\'a0\')',
                      'set_error_handler(\'a\')',
                     );

$expected_not = array('set_error_handler(\'a12\')',
                      'set_error_handler(\'a13\')',
                      'set_error_handler(\'a14\')',
                     );

?>
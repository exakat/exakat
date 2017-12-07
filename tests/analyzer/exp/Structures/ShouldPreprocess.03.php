<?php

$expected     = array('pow(2, 10)',
                      'ord(\'A\')',
                      'strtolower(\'yes\')',
                     );

$expected_not = array('set_time_limit(0)',
                      'get_defined_vars( )',
                      'print "ERROR: Failed to read data\\n"',
                     );

?>
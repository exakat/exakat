<?php

$expected     = array('echo 1',
                      'print 2',
                      'print_r(3)',
                      'debug_print_backtrace( )',
                      'A',
                     );

$expected_not = array('var_export($a, true)',
                      '$x = strtolower($a)',
                     );

?>
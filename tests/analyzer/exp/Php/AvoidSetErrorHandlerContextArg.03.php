<?php

$expected     = array('set_error_handler(array(\'foo\', \'a0\'))',
                      'set_error_handler(array(\'foo\', \'a\'))',
                     );

$expected_not = array('set_error_handler(array(\'foo\', \'a14\'))',
                      'set_error_handler(array(\'foo\', \'a13\'))',
                      'set_error_handler(array(\'foo\', \'a12\'))',
                     );

?>
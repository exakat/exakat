<?php

$expected     = array('posix_access($file, POSIX_R_OK | POSIX_W_OK | POSIX_A_OK)',
                      'posix_get_last_error( )',
                      'posix_strerror($error)',
                      'POSIX_R_OK',
                      'POSIX_W_OK',
                     );

$expected_not = array('POSIX_A_OK',
                      'posix_get_next_error( )',
                     );

?>
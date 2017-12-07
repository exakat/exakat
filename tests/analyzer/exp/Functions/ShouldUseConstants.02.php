<?php

$expected     = array('glob(\'ko1\', 1)',
                      'glob(\'ko2\', "1")',
                      'glob(\'ko3\', \'1\')',
                      'glob(\'ko4\', null)',
                      'glob(\'ko5\', FILE_APPEND | 1)',
                      'glob(\'ko6\', \\FILE_APPEND + LOCK_EX)',
                     );

$expected_not = array('glob(\'ok1\', FILE_USE_INCLUDE_PATH)',
                      'glob(\'ok2\', \\FILE_USE_INCLUDE_PATH)',
                      'glob(\'ok3\', FILE_APPEND | LOCK_EX)',
                      'glob(\'ok4\', FILE_APPEND | \\LOCK_EX)',
                      'glob(\'ok5\', \\FILE_APPEND | LOCK_EX)',
                      'glob(\'ok7\', \\FILE_APPEND | (LOCK_EX | FILE_APPEND)',
                     );

?>
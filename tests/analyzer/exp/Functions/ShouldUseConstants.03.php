<?php

$expected     = array('setlocale("1", \'ko2\')',
                      'setlocale(\'1\', \'ko3\')',
                      'setlocale(null, \'ko4\')',
                      'setlocale(1, \'ko1\')',
                      'setlocale(FILE_APPEND | 1, \'ko5\')',
                     );

$expected_not = array('setlocale(FILE_USE_INCLUDE_PATH, \'ok1\')',
                      'setlocale(\\FILE_USE_INCLUDE_PATH, \'ok2\')',
                      'setlocale(FILE_APPEND | LOCK_EX, \'ok3\')',
                      'setlocale(FILE_APPEND | \\LOCK_EX, \'ok4\')',
                      'setlocale(\\FILE_APPEND | LOCK_EX, \'ok5\')',
                      'setlocale(\\FILE_APPEND + LOCK_EX, \'ko6\')',
                      'setlocale(\\FILE_APPEND | (LOCK_EX | FILE_APPEND), \'ok7\')',
                     );

?>
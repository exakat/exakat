<?php

$expected     = array('file_put_contents(\'file.txt\', \'ko1\', 1)',
                      'file_put_contents(\'file.txt\', \'ko2\', "1")',
                      'file_put_contents(\'file.txt\', \'ko3\', \'1\')',
                      'file_put_contents(\'file.txt\', \'ko4\', null)',
                      'file_put_contents(\'file.txt\', \'ko5\', FILE_APPEND | 1)',
                      'file_put_contents(\'file.txt\', \'ko6\', \\FILE_APPEND + LOCK_EX)',
                      'file_put_contents(\'file.txt\', \'ko7\', \\FILE_APPEND | (LOCK_EX & (FILE_APPEND | 1)))',
                     );

$expected_not = array('file_put_contents(\'file.txt\', \'ok7\', \\FILE_APPEND | ( LOCK_EX | FILE_APPEND))',
                      'file_put_contents(\'file.txt\', \'ok6\', \FILE_APPEND | (LOCK_EX | FILE_APPEND))',
                     );

?>
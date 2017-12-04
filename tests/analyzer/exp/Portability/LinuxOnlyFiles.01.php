<?php

$expected     = array('file_get_contents(\'/dev/random\', \'r\')',
                      'file_get_contents(\'/proc/stat\', \'r\')',
                      'fopen(\'/dev/urandom\', \'r\')',
                      'file_get_contents(\'/proc/meminfo\', \'r\')',
                      'file_get_contents(\'/etc/hosts\', \'r\')',
                      'file_get_contents(\'/etc/group\', \'r\')',
                      'file_get_contents(\'/etc/passwd\', \'r\')',
                     );

$expected_not = array('file_get_contents(\'/etc/host\', \'r\')',
                      'file_get_contents(\'/etc/GROUP\', \'r\')',
                     );

?>
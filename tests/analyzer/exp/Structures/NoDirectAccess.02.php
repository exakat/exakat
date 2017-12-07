<?php

$expected     = array('defined(\'IN_UC3\') or die(\'Access Denied3\')',
                      'defined(\'IN_UC4\') || exit(\'Access Denied2\')',
                      '!defined(\'IN_UC2\') and exit(\'Access Denied2\')',
                      '!defined(\'IN_UC\') && exit(\'Access Denied\')',
                     );

$expected_not = array('defined(\'IN_UC5\') xor die(\'Access Denied5\')',
                      'defined(\'IN_UC6\') or print(\'Access Denied6\')',
                     );

?>
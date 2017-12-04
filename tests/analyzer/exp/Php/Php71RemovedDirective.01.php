<?php

$expected     = array('\\ini_get(\'session.hash_bits_per_charactor\')',
                      'ini_set(\'session.hash_function\', 1)',
                      'ini_restore(\'session.entropy_length\')',
                      'ini_alter(\'session.entropy_file\', 3)',
                     );

$expected_not = array('\\ini_restore(\'session.auto_start\')',
                     );

?>
<?php

$expected     = array('fopen(\'a.txt\', \'r\')',
                      'fopen(\'a.txt\', \'r+\')',
                     );

$expected_not = array('fopen(\'a.txt\', \'rb\')',
                      'fopen(\'a.txt\', \'br\')',
                      'fopen(\'a.txt\', \'rb+\')',
                      'fopen(\'a.txt\', \'br+\')',
                     );

?>
<?php

$expected     = array('fopen(\'a.txt\', \'tr\')',
                      'fopen(\'a.txt\', \'rt\')',
                      'fopen(\'a.txt\', \'tr+\')',
                      'fopen(\'a.txt\', \'rt+\')',
                     );

$expected_not = array('fopen(\'a.txt\', \'rb\')',
                      'fopen(\'a.txt\', \'rb+\')',
                     );

?>
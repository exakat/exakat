<?php

$expected     = array('`ls -la`',
                      'exec(\'ls -la\')',
                      'popen(\'ls -la\', \'r\')',
                      'shell_exec(\'ls -la\')',
                     );

$expected_not = array('fopen(\'ls -la\', \'r\')',
                     );

?>
<?php

$expected     = array('explode(\'/\', substr($a, $b1) ?? \'\')',
                      'explode(\'/\', shell_exec($a, $b2))',
                      'explode(\'/\', strpos($a, $b4))',
                     );

$expected_not = array('unlink(__FILE__)',
                      'explode(\'/\', shell_exec($a, $b2) ?? \'\')',
                      'explode(\'/\', shell_exec($a, $b2) ?: \'\')',
                      'explode(\'/\', strpos($a, $b4) ?: \'\')',
                     );

?>
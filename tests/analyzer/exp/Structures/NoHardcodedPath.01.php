<?php

$expected     = array('fopen(\'a\', \'r\')',
                      'file_put_contents("a$b", \'c\')',
                      'file_get_contents("a" . B, \'c\')',
                      'file_put_contents("a{b}", \'c\')',
                     );

$expected_not = array('glob(__DIR__, \'r\')',
                      'unlink("{$c}d")',
                      'rmkdir($e."f")',
                      'file_get_contents("a" . $b, \'c\')',
                     );

?>
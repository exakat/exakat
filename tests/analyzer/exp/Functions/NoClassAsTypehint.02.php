<?php

$expected     = array('X ...$x1',
                      '\\X ...$x1',
                     );

$expected_not = array('\\I',
                      'I',
                      'UNKNOWN',
                      '\\UNKNOWN',
                      'string',
                      'PDO',
                      'Closure',
                     );

?>
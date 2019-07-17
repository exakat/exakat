<?php

$expected     = array('X',
                      '\\X',
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
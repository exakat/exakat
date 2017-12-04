<?php

$expected     = array('eval(\'$y = 4;\')',
                      'EVAL(\'$y = 3;\')',
                      'eval(\'$y = 1;\')',
                      'create_function(\'$y\', \'$y = 2;\')',
                     );

$expected_not = array('create_function(\'$z\', \'$y = 6;\')',
                      'create_function(\'$y\', \'$y = 5;\')',
                     );

?>
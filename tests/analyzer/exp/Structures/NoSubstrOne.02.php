<?php

$expected     = array('substr($token[1], 4, -+-1)',
                      'substr($token[1], 1, 1)',
                     );

$expected_not = array('substr($token[1][2], 0, -1)',
                      'substr($token[1][2][3], 1, -1)',
                      'substr($token[1], 1, -1)',
                      'substr($token[1][2][3][4], 1, -1)',
                      'substr()',
                     );

?>
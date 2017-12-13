<?php

$expected     = array('array(1, 2, 3.4, 5)', 
                      'array(\'a\' . \'b\', \'c\' . \'d\', \'e\')',
                      'array(1, 2.3, 4.5)', 
                      'array(\'a\', \'b\', \'c\' . \'d\')',
                     );

$expected_not = array('array(1, 2, 3, 4)', 
                      'array(1, 2 . 3, 4 . 5)',
                      'array(\'a\', \'b\', \'c\' . \'d\')', 
                     );

?>
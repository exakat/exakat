<?php

$expected     = array('array(\'a\', \'b\', \'c\' . \'d\')',
                      'array(\'a\' . \'b\', \'c\' . \'d\', \'e\')',
                     );

$expected_not = array('array(1, ((int) $a) . "2", 4.5)',
                     );

?>
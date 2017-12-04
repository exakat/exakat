<?php

$expected     = array('array(\'a\', \'b\', \'c\')',
                      '[\'b\', \'a\', \'c\']',
                      'array(\'a\', \'c\', \'b\')',
                     );

$expected_not = array('array(\'a\', \'b\', \'d\')',
                      '[\'b\', \'a\']',
                      '[\'b\', \'a\', \'c\', \'d\']',
                     );

?>
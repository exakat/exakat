<?php

$expected     = array('array(\'a\', \'b\', \'c\')',
                      'array(\'a\', \'c\', \'b\')',
                      '[\'b\', \'a\', \'c\']',
                      'array(\'a\', \'b\', \'c\',  )',
                     );

$expected_not = array('array(\'a\', \'b\', \'d\')',
                      '[\'b\', \'a\']',
                      '[\'b\', \'a\', \'c\', \'d\']',
                     );

?>
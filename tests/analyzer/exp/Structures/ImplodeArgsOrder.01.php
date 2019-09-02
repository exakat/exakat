<?php

$expected     = array('implode(array( ), \'string\')',
                      'implode($b, X)',
                      'implode(MY_ARRAY, \'r\')',
                     );

$expected_not = array('implode($a, $b)',
                      'implode(\'string\', array())',
                     );

?>
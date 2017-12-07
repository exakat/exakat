<?php

$expected     = array('\\class_alias($a, $b)',
                      'class_alias(\'a\', \'b\')',
                      'CLASS_ALIAS($b, \'C\')',
                     );

$expected_not = array('class_alias(1,2)',
                     );

?>
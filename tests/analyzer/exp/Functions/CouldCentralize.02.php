<?php

$expected     = array('custom(\'b\', \'a4\', $c1)',
                      'custom(\'d\', \'a4\', $c2)',
                      'custom(\'d\', \'a4\', $c3)',
                      'custom(\'d\', \'a4\', $c4)',
                     );

$expected_not = array('str_replace(\'a2\', \'b\', $c)',
                      'str_replace(\'a2\', \'d\', $c)',
                      'str_replace(\'b\', \'a3\', $c)',
                      'str_replace(\'d\', \'a3\', $c)',
                      'str_replace(\'d\', \'a3\', $c)',
                     );

?>
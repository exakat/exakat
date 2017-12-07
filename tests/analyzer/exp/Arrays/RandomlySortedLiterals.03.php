<?php

$expected     = array('array(1, 44, 7)',
                      'array(1, 7, 44)',
                      '[44, 1, 7]',
                      'array(1, 44, 7,  )',
                     );

$expected_not = array('array(1, 44, 8)',
                      '[44, 1]',
                      '[44, 1, 7, 8]',
                     );

?>
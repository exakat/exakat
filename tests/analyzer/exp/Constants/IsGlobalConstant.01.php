<?php

$expected     = array('DEFINED_IN_GLOBAL',
                      'DEFINED_IN_GLOBAL2',
                      'DEFINED_IN_GLOBAL3',
                     );

$expected_not = array('DEFINED_IN_X',
                      'UNDEFINED_IN_GLOBAL',
                      '\\ABSOLUTE_CONSTANT',
                      'E_ALL',
                     );

?>
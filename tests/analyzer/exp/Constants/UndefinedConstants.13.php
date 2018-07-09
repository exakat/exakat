<?php

$expected     = array('\\A_CONST',
                      'A\\E_NOTICE',
                      'A_CONST',
                     );

$expected_not = array('ALABEL_NOT_A_CONST',
                      'ALABEL_NOT_A_CONST_IN_FUNCTION',
                      'E_NOTICE',
                      '\\E_NOTICE',
                     );

?>
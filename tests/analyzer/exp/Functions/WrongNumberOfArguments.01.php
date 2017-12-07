<?php

$expected     = array('\\crypt( )',
                      'crypt( )',
                      'CRYpt(4, 5, 6, 9)',
                      '\\crypt(4, 5, 6, 8)',
                      'crypt(4, 5, 6, 7)',
                     );

$expected_not = array('unknown_function(1,2,3,4,5)',
                      'phpinfo(1,2,3)',
                      'assert_options(6,7,8,9)',
                     );

?>
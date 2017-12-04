<?php

$expected     = array('\\GETOPT(1)',
                      '\\getopt( )',
                      '\\getopt(2, 3)',
                      'CRYpt(1)',
                      'crypt( )',
                     );

$expected_not = array('phpinfo( )',
                      'assert_options( )',
                      'crypt(2,3)',
                      'crypt(4,5,6,7)',
                     );

?>
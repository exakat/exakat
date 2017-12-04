<?php

$expected     = array('print 1',
                      'echo 6',
                      'echo 9',
                     );

$expected_not = array('print 2',
                      'print 3',
                      'echo 4',
                      'header(5)',
                      'print 7',
                      'echo 10',
                     );

?>
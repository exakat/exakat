<?php

$expected     = array('EMPTY2 . \'\'',
                      'EMPTY3 . NULL',
                      'EMPTY1 . $e',
                      'EMPTY2 . $e',
                      'EMPTY3 . $e',
                      'EMPTY4 . $e',
                      '\'\' . EMPTY1',
                      'EMPTY4 . \'d\'',
                     );

$expected_not = array('NOT_EMPTY . $e',
                      'NOT_EMPTY2 . $e',
                     );

?>
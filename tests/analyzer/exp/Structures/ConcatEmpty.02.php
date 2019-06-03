<?php

$expected     = array('$h .= EMPTY_STRING',
                      '\'\' . \'b\' . \'\'',
                      '\'\' . \'\'',
                     );

$expected_not = array('$a .= $b',
                      '$d . $e',
                      '$g . PHP_EOL',
                     );

?>
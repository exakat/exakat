<?php

$expected     = array('$a == true',
                      'FALSE > $c',
                      '$d > TRUE',
                     );

$expected_not = array('$e >= 1',
                      'false !== $b',
                     );

?>
<?php

$expected     = array('$a = new sadf($b = 3)',
                      'while (false !== ($x2 = fgets($fp))) { /**/ } ',
                     );

$expected_not = array('$x1 = fgets($fp)',
                      '$x3 = fgets($fp)',
                     );

?>
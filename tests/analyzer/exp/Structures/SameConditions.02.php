<?php

$expected     = array('if($b > 2) { /**/ } else { /**/ } ',
                     );

$expected_not = array('$b > 1',
                      '$b > 3',
                      '$e != \'F\'',
                     );

?>
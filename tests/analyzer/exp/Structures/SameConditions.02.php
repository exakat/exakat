<?php

$expected     = array('if($b > 2) { /**/ } else { /**/ } ',
                      'if($b > 3) { /**/ } else { /**/ } ',
                      'if($b > 1) { /**/ } else { /**/ } ',
                      'if($e != \'F\') { /**/ } else { /**/ } ',
                     );

$expected_not = array('$b > 1',
                      '$b > 3',
                      '$e != \'F\'',
                     );

?>
<?php

$expected     = array('if($b == 4) { /**/ } else { /**/ } ',
                      'if($b == 3) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($b == 1) { /**/ } else { /**/ } ',
                      'if($b == 2) { /**/ } else { /**/ } ',
                     );

?>
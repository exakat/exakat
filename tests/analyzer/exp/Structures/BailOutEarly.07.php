<?php

$expected     = array('if($a3 == 3) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($a == 3) { /**/ } ',
                      'if($a == 4) { /**/ } else { /**/ } ',
                      'if($a == 1) { /**/ } else { /**/ } ',
                      'if($a == 2) { /**/ } else { /**/ } ',
                     );

?>
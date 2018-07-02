<?php

$expected     = array('if($a2) { /**/ } else { /**/ } ',
                      'if($a1) { /**/ } else { /**/ } ',
                      'if($a3) { /**/ } else { /**/ } ',
                      'if($a4) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($a22) { /**/ } else { /**/ } ',
                      'if($a44) { /**/ } else { /**/ } ',
                     );

?>
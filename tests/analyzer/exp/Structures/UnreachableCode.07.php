<?php

$expected     = array('if($a5) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($a1) { /**/ } else { /**/ } ',
                      'if($a2) { /**/ } else { /**/ } ',
                      'if($a3) { /**/ } ',
                      'if($a4) { /**/ } else { /**/ } ',
                     );

?>
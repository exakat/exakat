<?php

$expected     = array('if($a2) { /**/ } else { /**/ } ',
                      'if($a1) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($a3) { /**/ } ',
                      'if($a4) { /**/ } else { /**/ } ',
                      'if($a5) { /**/ } ',
                     );

?>
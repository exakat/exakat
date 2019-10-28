<?php

$expected     = array('if($a1) { /**/ } ',
                      'if($a2) { /**/ } ',
                     );

$expected_not = array('if($a3) { /**/ } else { /**/ } ',
                      'if($a4) { /**/ } else { /**/ } ',
                     );

?>
<?php

$expected     = array('if($b) { /**/ } else { /**/ } ',
                      'if($a) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($c) { /**/ } else { /**/ } ',
                      'if($a2) { /**/ } else { /**/ } ',
                      'if($a3) { /**/ } else { /**/ } ',
                     );

?>
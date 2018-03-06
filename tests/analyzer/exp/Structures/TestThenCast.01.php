<?php

$expected     = array('if($a1 != 0) { /**/ } ',
                      'if($a4 != 0) { /**/ } ',
                     );

$expected_not = array('if($a2 != 0) { /**/ } ',
                      'if($a3 != 0) { /**/ } ',
                      'if($a5 != 1) { /**/ } ',
                     );

?>
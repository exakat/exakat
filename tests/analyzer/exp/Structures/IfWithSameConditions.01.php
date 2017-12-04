<?php

$expected     = array('if($a == 2) { /**/ } ',
                      'if($b == 3) { /**/ } ',
                      'if($b == 3) { /**/ } ',
                      'if($b == 2) { /**/ } ',
                     );

$expected_not = array('if($a == 1) { /**/ } ',
                      'if($c == 3) { /**/ } ',
                     );

?>
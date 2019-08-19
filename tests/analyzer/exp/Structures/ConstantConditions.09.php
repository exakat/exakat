<?php

$expected     = array('if(!@$equal = 1) { /**/ } ',
                      'if(($equal = \'00\' . 0)) { /**/ } ',
                      'if($a = $b = $c = 1) { /**/ } ',
                     );

$expected_not = array('if(!@$plus += 1) { /**/ } ',
                     );

?>
<?php

$expected     = array('elseif($b) { /**/ } else { /**/ } ', 
                      'elseif($a) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($a2) { /**/ } else { /**/ } ',
                      'if($a3) { /**/ } else { /**/ } ',
                     );

?>
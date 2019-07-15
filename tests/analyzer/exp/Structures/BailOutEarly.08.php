<?php

$expected     = array('if($a == 11) { /**/ } else { /**/ } ', 
                      'if($a == 12) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($a = 1) { /**/ } else { /**/ } ', 
                     );

?>
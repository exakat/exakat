<?php

$expected     = array('if(!customCall($a3)) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if(!empty($a)) { /**/ } ',
                      'if(empty($a2)) { /**/ } else { /**/ } ',
                     );

?>
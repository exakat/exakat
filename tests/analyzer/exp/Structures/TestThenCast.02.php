<?php

$expected     = array('if($a == \'b\') { /**/ } elseif(is_numeric($a)) { /**/ } ',
                      'if($a == \'c\') { /**/ } else { /**/ } ',
                      'if($a == \'d\') { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($a == \'a\') { /**/ } elseif(is_numeric($a)) { /**/ } ',
                     );

?>
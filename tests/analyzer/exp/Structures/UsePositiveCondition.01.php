<?php

$expected     = array('if($a4 !== 3) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($a) { /**/ } ',
                      'if($a2) { /**/ } else { /**/ } ',
                      'if($a3 !== 3) { /**/ } else { /**/ } ',
                     );

?>
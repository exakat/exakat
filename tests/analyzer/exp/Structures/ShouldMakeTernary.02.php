<?php

$expected     = array('if($c[1] === 3) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($c[1] === 2) { /**/ } else { /**/ } ',
                      'if($c[1] === 4) { /**/ } else { /**/ } ',
                     );

?>
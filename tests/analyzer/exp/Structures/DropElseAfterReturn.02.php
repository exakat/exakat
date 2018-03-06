<?php

$expected     = array('if($constant === 3) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($constant === 1) { /**/ } else { /**/ } ',
                      'if($constant === 2) { /**/ } else { /**/ } ',
                     );

?>
<?php

$expected     = array('if(1) { /**/ } elseif(2) { /**/ } elseif(3) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if(10) { /**/ } else { /**/ } ',
                      'if(11) { /**/ } elseif(21) { /**/ } else { /**/ } ',
                     );

?>
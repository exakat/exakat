<?php

$expected     = array('if(21) { /**/ } elseif(31) { /**/ } else { /**/ } ',
                      'if(20) { /**/ } elseif(30) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if(10) { /**/ } else { /**/ } ',
                      'if(11) { /**/ } else { /**/ } ',
                      'if(12) { /**/ } else { /**/ } ',
                     );

?>
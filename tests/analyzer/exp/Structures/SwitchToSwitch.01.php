<?php

$expected     = array('if(1) { /**/ } elseif(2) { /**/ } elseif(3) { /**/ } else { /**/ } ',
                      'if(11) { /**/ } elseif(12) { /**/ } elseif(13) { /**/ } ',
                      'if(31) { /**/ } elseif(32) { /**/ } elseif(33) { /**/ } elseif(34) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if(21) { /**/ } elseif(22) { /**/ } else { /**/ } ',
                     );

?>
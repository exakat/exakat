<?php

$expected     = array('if($a >= 8)  /**/  else  /**/  ',
                      'if($a == 3) { /**/ } else { /**/ } ',
                      'if($a > 5) { /**/ } else { /**/ } ',
                      'if($a > 6) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($a != 4) { /**/ } else { /**/ } ',
                      'if($a > 7) { /**/ } else { /**/ } ',
                     );

?>
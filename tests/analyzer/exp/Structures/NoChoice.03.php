<?php

$expected     = array('if($a) { /**/ } else { /**/ } ',
                      'if($a == 2) :   /**/  else :  /**/   endif',
                      'if($c == 2) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if ($c == 3) { /**/ } else { /**/ }',
                      'if ($c == 4) { /**/ } else { /**/ }',
                      'if ($a == 1) : ; else : ; endif',
                     );

?>
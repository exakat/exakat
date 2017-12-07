<?php

$expected     = array('if($a1)  /**/  ',
                     );

$expected_not = array('if ($a) { /**/ }',
                      'if ($a2) { /**/ }',
                      'if ($b) { /**/ }',
                      'if ($b2) { /**/ }',
                     );

?>
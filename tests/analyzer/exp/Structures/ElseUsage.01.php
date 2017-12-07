<?php

$expected     = array('if($withElseifAndElse) { /**/ } else { /**/ } ',
                      'if($withElse) { /**/ } else { /**/ } ',
                      'if($withinElseAndWithElse) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($noElse) {$z--;} ',
                      'if($withElseifNoElse) { $b++; } ',
                     );

?>
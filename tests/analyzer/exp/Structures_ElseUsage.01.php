<?php

$expected     = array('if ($withinElseAndWithElse) { ; } else { ; }',
                      'if ($withElseifAndElse) { ; } else { ; }',
                      'if ($withElse) { ; } else { ; }');

$expected_not = array('if ($noElse) {$z--;}',
                      'if ($withElseifNoElse) { $b++; }');

?>
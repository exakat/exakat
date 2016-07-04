<?php

$expected     = array('if ($c == 2) { /**/ } else { /**/ }', 
                      'if ($a == 2) : ; else : ; endif');

$expected_not = array('if ($c == 3) { /**/ } else { /**/ }', 
                      'if ($c == 4) { /**/ } else { /**/ }', 
                      'if ($a == 1) : ; else : ; endif');

?>
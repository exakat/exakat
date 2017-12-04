<?php

$expected     = array('while (list($a) = each($c)) { /**/ } ',
                      'while (list($a, $b) = each($c)) { /**/ } ',
                     );

$expected_not = array('while($i < 10) { /**/ }',
                     );

?>
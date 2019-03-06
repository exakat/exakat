<?php

$expected     = array('array_map($a, \'foo\')',
                      'foreach($foo as $a) { /**/ } ',
                     );

$expected_not = array('while($a1 == $b) { /**/ } ',
                      'while($a2 == $b) { /**/ } ',
                     );

?>
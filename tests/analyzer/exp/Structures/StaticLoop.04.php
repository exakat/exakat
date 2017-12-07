<?php

$expected     = array('while ($a3++) /**/ ',
                      'do /**/ while($b2++)',
                      'while ($a5++) { /**/ } ',
                     );

$expected_not = array('do /**/ while ($a++)',
                     );

?>
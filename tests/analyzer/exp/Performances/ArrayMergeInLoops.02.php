<?php

$expected     = array('foreach($c as $b) { /**/ } ',
                      'for($i = 0 ; $i < 10 ; $i++) { /**/ } ',
                      'do { /**/ } while($ddo > 0)',
                      'while ($dw > 0) { /**/ } ',
                     );

$expected_not = array('array_merge_recursive($a, $b)',
                      'do { /**/ } while($xxx > 0)',
                     );

?>
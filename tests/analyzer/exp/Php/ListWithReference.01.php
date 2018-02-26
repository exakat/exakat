<?php

$expected     = array('list($a, &$b1)',
                      '[$a, &$b3]',
                     );

$expected_not = array('list($a, $b2) ',
                      '[$a,  $b4]',
                     );

?>
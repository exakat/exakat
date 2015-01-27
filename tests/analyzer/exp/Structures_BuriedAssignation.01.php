<?php

$expected     = array('$deep1 = 3',
                      '$deep3 = $t->strlen($de)',
                      '$deep2 = strlen($d) - 1',
                      '$deep4 = 4');

$expected_not = array('$normal1 = 2',
                      '$normal = 3;',);

?>
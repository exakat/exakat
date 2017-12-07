<?php

$expected     = array('false',
                      '$j < 1',
                      '$a2 + 1',
                      '$i < $b2',
                      '$j3 * 2',
                     );

$expected_not = array('$j < $b2',
                      '++$i',
                     );

?>
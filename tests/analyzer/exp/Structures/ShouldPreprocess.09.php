<?php

$expected     = array('$a = 1',
                      '$a = 13',
                      '$a .= 16',
                      '$a .= 26',
                      '$a .= 23',
                     );

$expected_not = array('$a = 12',
                      '$a = 14',
                      '$a = 15',
                      '$a = 17',
                     );

?>
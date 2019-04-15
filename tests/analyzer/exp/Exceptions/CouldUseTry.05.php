<?php

$expected     = array('4 / E',
                      '5 / $d',
                      '3 << $f',
                      '5 >> -F',
                      '3 << -3',
                     );

$expected_not = array('3 << -3',
                      '3 << $f',
                      '5 >> -F',
                      '5 >> +F',
                     );

?>
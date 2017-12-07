<?php

$expected     = array('$used_once_in_x',
                      '$used_once_in_x2',
                      '$used_once',
                      '$b = 1',
                      '$a',
                      'Stdclass $d = null',
                      'Stdclass $d = null',
                      'Stdclass $c',
                      '$b = 1',
                      'Stdclass $c',
                      '$a',
                     );

$expected_not = array('$used_twice',
                     );

?>
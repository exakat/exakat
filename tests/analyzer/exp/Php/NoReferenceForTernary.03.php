<?php

$expected     = array('1 ? $b : $b',
                      '1 ? 3 : $b',
                      '1 ? $b : 2',
                     );

$expected_not = array('$c = 1 ? $a : 2',
                     );

?>
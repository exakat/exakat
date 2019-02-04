<?php

$expected     = array('$a = ($a) << 1',
                      '$a = ($a) >> 1',
                      '$a = ($a << 1)',
                      '$a = ($a >> 1)',
                     );

$expected_not = array('$a = ($b) >> 1',
                     );

?>
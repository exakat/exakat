<?php

$expected     = array('$a ^ $a',
                      '$a || $a',
                      '$a and $a',
                      '$a ^ ($a)',
                      '($a) ^ ($a)',
                      '($a) ^ $a',
                     );

$expected_not = array('$a ^ $b',
                     );

?>
<?php

$expected     = array('($c && $b)',
                      '($c || $b)',
                      '($c ^ $b)',
                     );

$expected_not = array('($c and $b)',
                      '($c or $b)',
                      '($c xor $b)',
                     );

?>
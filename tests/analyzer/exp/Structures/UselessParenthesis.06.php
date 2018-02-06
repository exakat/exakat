<?php

$expected     = array('($a * $b)',
                      '$a + $b',
                      '(($a + $b) * $c)',
                      '(($a * $b) + $c)',
                      '(($a * $b) * $c)',
                      '(($a + $b) + $c)',
                     );

$expected_not = array('($a + $b) * $c',
                      '($a * $b) + $c',
                     );

?>
<?php

$expected     = array('$a = $b == 2 ? 5 : $a = 3',
                      '$a = $d ?: $a = 3',
                      '$a = $b == 2 ? $a = 3 : 5',
                      '$a = $d ?? $a = 3',
                     );

$expected_not = array('$a = $b == 2 ? 6 : 5',
                      '$a = $d ?: 34',
                      '$a = $d ?? 44',
                     );

?>
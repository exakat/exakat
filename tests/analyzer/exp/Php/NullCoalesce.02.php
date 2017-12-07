<?php

$expected     = array('0 || 2 ?? 3',
                      '0 || $d ?? $s',
                      '3 ?? 4',
                     );

$expected_not = array('3 ?: 44',
                      '3 ? 5 : 4',
                     );

?>
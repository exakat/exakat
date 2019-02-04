<?php

$expected     = array('$int1 = $int2 + $int1',
                      '$int1 = $int1 + $int2',
                      '$int1 = $int1 * $int2',
                      '$int1 = $int2 * $int1',
                      '$int1 = $int1 | $int2',
                      '$int1 = $int1 - $int2',
                      '$int1 = $int1 ** $int2',
                      '$int1 = $int1 / $int2',
                      '$int1 = $int1 >> $int2',
                      '$int1 = $int1 << $int2',
                     );

$expected_not = array('$int1 = $int2 + $int3',
                      '$int1 = $int2 * $int3',
                      '$int1 = $int2 / $int1',
                      '$int1 = $int1 / $int2',
                      '$int1 = $int2 - $int1',
                      '$int1 = $int1 - $int2',
                     );

?>
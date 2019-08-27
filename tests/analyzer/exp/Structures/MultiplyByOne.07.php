<?php

$expected     = array('12 / 12 * $b',
                      '13 % 12 * $b',
                      '$b * 12 / 12',
                     );

$expected_not = array('$b / 12 / 12',
                      '$b * 12 * 12',
                      '$b * 12 / 12',
                      '12 % 12 * $b',
                     );

?>
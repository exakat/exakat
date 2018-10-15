<?php

$expected     = array('$a = 4',
                      '$a = 3',
                      '$b = 2',
                      '$a = 5',
                      '$g = 5',
                      '$j = 4',
                      '$g = 6',
                      '$j = 4',
                     );

$expected_not = array('$g + $h + i + ($j = 4) + f + $k ',
                      '$a + 44',
                      '$a = $a + 44',
                     );

?>
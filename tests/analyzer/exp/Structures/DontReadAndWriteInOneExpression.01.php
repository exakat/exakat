<?php

$expected     = array('$a = 4',
                      '$a = 3',
                      '$b = 2',
                      '$g = 5',
                      '$g = 6',
                     );

$expected_not = array('$g + $h + i + ($j = 4) + f + $k ',
                      '$a + 44',
                      '$a = $a + 44',
                      '$j = 4',
                      '$j = 44',
                     );

?>
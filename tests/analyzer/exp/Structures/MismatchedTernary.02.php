<?php

$expected     = array('$b ? 1 : \'c\'',
                      '$b ? 1.0 : 3',
                     );

$expected_not = array('$b ? "c" : \'c\'',
                     );

?>
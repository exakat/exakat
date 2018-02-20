<?php

$expected     = array('$a *= true',
                      '$a *= \'1\'',
                      '$a *= 1.0',
                      '$a *= 1.0000',
                     );

$expected_not = array('$a *= "1.1"',
                      '$a *= "2.1"',
                     );

?>
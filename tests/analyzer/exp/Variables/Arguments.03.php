<?php

$expected     = array('$property2',
                      '$property2',
                      '$property4',
                      '$property4',
                      '$property5 = 3',
                      '$property5',
                      '$property6 = 2',
                      '$property6',
                      'Stdclass $property7 = null',
                      '$property7',
                      'Stdclass $property8 = null',
                      '$property8',
                     );

$expected_not = array('$property',
                      '$property3',
                     );

?>
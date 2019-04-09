<?php

$expected     = array('$r1',
                      '$r2',
                      '$r3',
                      '$w1',
                      '$w2',
                      '$w3',
                      '$r4', 
                      '$r6', 
                      '$r5',
                     );

$expected_not = array('$read3b',
                      '&$written3b',
                      '$read3a',
                      '&$written3a',
                      '$read2a',
                      '&$written2a',
                      '$read1',
                      '&$written1',
                     );

?>
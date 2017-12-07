<?php

$expected     = array('$readadd[1]',
                      '$readadd[1][2]',
                      '$readadd[1]',
                      '$readadd[1][2][3]',
                      '$readadd[1][2]',
                      '$readadd[1]',
                      '$readAssignation[3]',
                      '$read2[1]',
                      '$read3[1]',
                      '$read[1]',
                      '$written2[1]',
                      '$ignored3[3]',
                      '$written[2]',
                      '$written3[2]',
                     );

$expected_not = array('$written_only[3]',
                     );

?>
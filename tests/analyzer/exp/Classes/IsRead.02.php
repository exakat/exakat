<?php

$expected     = array('$read->a',
                      '$written->b',
                      '$written2->d',
                      '$ignored3->g',
                      '$written3->f',
                      '$read2->c',
                      '$read3->e',
                     );

$expected_not = array('$writtenOnly',
                     );

?>
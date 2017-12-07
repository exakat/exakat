<?php

$expected     = array('$y->a',
                      '$y[1]',
                      '$a',
                     );

$expected_not = array('$y->a',
                      '$y[a]',
                      '$b',
                     );

?>
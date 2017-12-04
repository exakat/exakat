<?php

$expected     = array('$c1 instanceof parent',
                      '$c3 instanceof parent',
                     );

$expected_not = array('$c2 instanceof parent',
                      '$a1 instanceof self',
                      '$a2 instanceof self',
                      '$a3 instanceof self',
                      '$b1 instanceof static',
                      '$b2 instanceof static',
                      '$b3 instanceof static',
                     );

?>
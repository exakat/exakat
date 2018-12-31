<?php

$expected     = array('$a1 = $b1 and $c1',
                      '$a1 = $d3 and ($b3 or $c3)',
                     );

$expected_not = array('($b2 and $c2)',
                      '$b2 and $c2',
                     );

?>
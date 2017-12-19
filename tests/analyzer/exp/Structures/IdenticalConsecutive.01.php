<?php

$expected     = array('$a = $d->e(A::$c, 1)',
                      '$a = $c + 1',
                     );

$expected_not = array('$A = $C + 1',
                      '$B = $C + 2',
                      '$a1 = $c1->a(1)',
                     );

?>
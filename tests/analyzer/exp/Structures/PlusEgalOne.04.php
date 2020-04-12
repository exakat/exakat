<?php

$expected     = array('$a = C1 + $a',
                      '$a = $a + A::c1',
                      '$a = $a + 1',
                     );

$expected_not = array('$a = C2 + $a',
                      '$a = $a + A::c2',
                     );

?>
<?php

$expected     = array('$a = $b = new C',
                      '$c = $d = clone $b',
                      '$f = $g = foo( )',
                      '$f1 = $g1 = foo1( )',
                     );

$expected_not = array('$f2 = $g2 = foo2( )',
                     );

?>
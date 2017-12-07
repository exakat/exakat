<?php

$expected     = array('$a ?: $a',
                      '$a->a ?: $a->a',
                      'A::$B ?: A::$B',
                      '$a[1] ?: $a[1]',
                     );

$expected_not = array('$a[1] ?: $b[1]',
                      '$a[1] ?: $a[2]',
                      '$a ?: $b',
                      '$a->a ?: $b->b',
                      'A::$B ?: B::$B',
                     );

?>
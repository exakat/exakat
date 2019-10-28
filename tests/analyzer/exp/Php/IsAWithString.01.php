<?php

$expected     = array('is_a($a, $b)',
                      'is_a(A, $b)',
                      'is_a(\\AA, $b)',
                      'is_a(x::A, $b)',
                      'is_a(\'s1\', $b)',
                      'is_a($a)',
                      'is_a($b === 1 ? \'c\' : \'d\', $b)',
                      'is_a($e ?: \'e\', $b)',
                      'is_a($ee ?? \'f\', $b)',
                      'is_a($x)',
                     );

$expected_not = array('is_a(new x, $b)',
                     );

?>
<?php

$expected     = array('is_subclass_of(\'s1\', $b, false)',
                      'is_subclass_of(A, $b, false)',
                      'is_subclass_of($e ?: \'e\', $b, false)',
                      'is_subclass_of($ee ?? \'f\', $b, false)',
                      'is_subclass_of(\\AA, $b, false)',
                      'is_subclass_of(x::A, $b, false)',
                      'is_subclass_of($x, $b, false)',
                      'is_subclass_of($a, $b, false)',
                     );

$expected_not = array('is_subclass_of(new x, $b, false)',
                      'is_subclass_of(new x, $b, true)',
                     );

?>
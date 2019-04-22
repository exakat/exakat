<?php

$expected     = array('array_merge(...$a)',
                      'array_merge(...$a1[1])',
                      'array_merge(...$a->m)',
                      'array_merge(...$a1::C)',
                      'array_merge(...$a1::$C::$D::$E)',
                      'array_merge(...$a5[3][4])',
                     );

$expected_not = array('array_merge( ...$a1->m ?? array( 1, 4))',
                      'array_merge( ...$a2 ?: array( 1, 4))',
                      'array_merge( ...$a3::C ?: array( 1, 4))',
                      'array_merge( ...$a4[3] ?: array( 1, 4))',
                     );

?>
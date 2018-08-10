<?php

$expected     = array('A::foo(1, 2,  )',
                      'A::foo(1, 2, 3,  )',
                      'A::foo(1, 2, 3, 4,  )',
                     );

$expected_not = array('A::foo(1, 2  )',
                      'A::foo(1, 2, 3  )',
                      'A::foo(1, 2, 3, 4  )',
                     );

?>
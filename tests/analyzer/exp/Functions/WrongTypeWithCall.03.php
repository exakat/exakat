<?php

$expected     = array('goo(1)',
                      'goo(1, 2, 3)',
                     );

$expected_not = array('hoo(1)',
                      'hoo([1])',
                      'hoo(1, 2, 3)',
                      'goo([1])',
                      'foo(1)',
                      'foo([1])',
                      'foo(1, 2, 3)',
                     );

?>
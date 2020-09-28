<?php

$expected     = array('foo(($a = 1 + 4))',
                      'foo((1))',
                      'foo(((2)))',
                      'foo($a = 1 + 3)',
                     );

$expected_not = array('foo($a)',
                      'foo( )',
                     );

?>
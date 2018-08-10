<?php

$expected     = array('$a->foo(1, 2,  )',
                      '$a->foo(1, 2, 3,  )',
                      '$a->foo(1, 2, 3, 4,  )',
                     );

$expected_not = array('$a->foo(1, 2  )',
                      '$a->foo(1, 2, 3  )',
                      '$a->foo(1, 2, 3, 4  )',
                     );

?>
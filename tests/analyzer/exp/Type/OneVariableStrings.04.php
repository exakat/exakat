<?php

$expected     = array('$a',
                      '$a->b',
                      '$a[1]',
                      '{$$c}',
                      '{$a->foo( )}',
                      '{$a::foo2( )}',
                      '{$a->foo( )}',
                     );

$expected_not = array('{foo()}',
                      '$a$b',
                     );

?>
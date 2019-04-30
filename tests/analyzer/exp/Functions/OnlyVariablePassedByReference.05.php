<?php

$expected     = array('$a->foo(\'a\')',
                      '$a->foo($y::D)',
                     );

$expected_not = array('$a->foo($x)',
                      '$a->foo($_GET)',
                      '$a->foo($y[1])',
                      '$a->foo($y->a)',
                      '$a->foo($y::$C);',
                     );

?>
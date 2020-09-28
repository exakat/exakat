<?php

$expected     = array('substr(foo3( ), 2, 3)',
                     );

$expected_not = array('substr(foo( ), 2, 3)',
                      'substr(foo2( ), 2, 3)',
                     );

?>
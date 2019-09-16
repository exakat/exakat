<?php

$expected     = array('foo(X::class)',
                      'foo(1 + 2)',
                      'foo(C)',
                      'foo(1)',
                      'foo(bar( ))',
                     );

$expected_not = array('foo(bar_with_ref( ))',
                     );

?>
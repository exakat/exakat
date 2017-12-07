<?php

$expected     = array('reset(foo( ))',
                      'getmxrr($a, foo( ))',
                      'foo::bar($a->boo( ), $a->boo( ))',
                      'foo::bar(foo( ), foo( ))',
                      'foo::bar(null, null)',
                      'bar(foo( ), foo( ))',
                      'bar(null, null)',
                      'bar($a->boo( ), $a->boo( ))',
                      'bar($a, foo(1))',
                      'bar($a, foo(1), foo(2))',
                      'bar(foo( ), $b, $c)',
                      'bar(foo( ), $b)',
                     );

$expected_not = array('getmxrr($a, foo( ))',
                     );

?>
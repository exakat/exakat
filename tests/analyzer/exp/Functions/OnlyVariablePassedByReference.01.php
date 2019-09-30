<?php

$expected     = array('getmxrr($a, foo( ))',
                      'foo::bar($a->boo( ), $a->boo( ))',
                      'foo::bar(foo( ), foo( ))',
                      'foo::bar(null, null)',
                      'bar(foo( ), foo( ))',
                      'bar(foo( ), $b)',
                      'bar(null, null)',
                      'bar($a->boo( ), $a->boo( ))',
                      'bar(foo( ), $b, $c)',
                      'bar(foo(1), $a)',
                     );

$expected_not = array('reset(foo( ))',
                      'getmxrr($a, foo( ))',
                      'bar($a, foo(1))',
                     );

?>
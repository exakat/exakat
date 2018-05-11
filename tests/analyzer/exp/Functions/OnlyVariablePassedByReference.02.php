<?php

$expected     = array('getmxrr($a, foo( ))',
                      'foo::bar($a->boo( ), $a->boo( ))',
                      'foo::bar(foo( ), foo( ))',
                      'foo::bar(null, null)',
                      'bar(foo( ), foo( ))',
                      'bar($a, foo(1), foo(2))',
                      'bar($a, foo(1))',
                      'bar(null, null)',
                      'bar($a->boo( ), $a->boo( ))',
                     );

$expected_not = array('reset(foo( ))',
                      'getmxrr($a, foo( ))',
                     );

?>
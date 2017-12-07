<?php

$expected     = array('reset(foo( ))',
                      'getmxrr($a, foo( ))',
                      'foo::bar($a->boo( ), $a->boo( ))',
                      'foo::bar(foo( ), foo( ))',
                      'foo::bar(null, null)',
                      'bar(foo( ), foo( ))',
                      'bar(foo( ), $b)',
                      'bar(null, null)',
                      'bar($a->boo( ), $a->boo( ))',
                      'bar(foo( ), $b, $c)',
                     );

$expected_not = array('getmxrr($a, foo( ))',
                     );

?>
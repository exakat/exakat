<?php

$expected     = array('uopz_set_return(Foo::class, "bar", true)',
                      'uopz_set_return(Foo::class, "bar", function (int $arg) : int { /**/ } , true)',
                      'uopz_set_return(Foo::class, "nope", 1)',
                      'uopz_set_return(Bar::class, "bar", null)',
                     );

$expected_not = array('uopz_set_something(Bar::class, "bar", null)',
                     );

?>
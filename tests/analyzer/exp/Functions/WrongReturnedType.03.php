<?php

$expected     = array('return $b',
                      'return new Unknown( )',
                      'return new Foo( )',
                      'return new Bar',
                      'return new FooFoo( )',
                      'return new interfaced( )',
                     );

$expected_not = array('return new FooAlias( )',
                     );

?>
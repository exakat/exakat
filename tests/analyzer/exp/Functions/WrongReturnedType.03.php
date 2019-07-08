<?php

$expected     = array('return $b', 
                      'new Unknown( )', 
                      'new Foo( )', 
                      'new Bar',
                     );

$expected_not = array('return new FooAlias( )',
                      '',
                     );

?>
<?php

$expected     = array('return $f',
                      'return $c',
                      'return $a',
                      'return 1',
                      'return new Foo( )',
                     );

$expected_not = array('return $b',
                      'return new Bar()',
                     );

?>
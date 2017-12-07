<?php

$expected     = array('$x->foo( )[0]',
                      'A::foo( )[2]',
                      'foo( )[0]',
                     );

$expected_not = array('normalFunction()',
                      '$normalArray[0]',
                      '$normalAppend[]',
                     );

?>
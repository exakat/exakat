<?php

$expected     = array('A::foo( )[2][2]',
                      'foo( )[0][1]',
                      '$x->foo( )[0][3]',
                     );

$expected_not = array('\\normalFunction()',
                      '$normalArray[0]->yes',
                      '$normalAppend[][2]',
                      '$normalAppend[1][]',
                     );

?>
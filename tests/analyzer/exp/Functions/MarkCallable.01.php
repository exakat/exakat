<?php

$expected     = array('\'callableString\'',
                      '\'MyClass::myCallbackMethod\'',
                      'array(\'string\', \'string\')',
                      '$c1',
                      '$c2',
                      '$array4',
                      '$array2',
                     );

$expected_not = array('$c3',
                      'c3',
                      'd4',
                      '$array1',
                      '$array2',
                      '\'b\'',
                      '$a1',
                      '$a2',
                     );

?>
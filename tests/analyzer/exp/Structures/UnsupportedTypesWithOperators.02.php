<?php

$expected     = array('--$b',
                      '$c | 3',
                      '$d ** 2',
                      '$a++',
                      'fooArray( ) + fooVoid( )',
                     );

$expected_not = array('$e % 3',
                      '3 & $f',
                     );

?>
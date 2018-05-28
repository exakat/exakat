<?php

$expected     = array('$source->a[$key]',
                      '$source->a[$key]',
                      '$source[\'a\'][$key]',
                      '$source[\'a\'][$key]',
                      '$_POST[$key]',
                      '$_POST[$key]',
                      'foo( )[$key]',
                      'foo( )[$key]',
                     );

$expected_not = array('foo()[0]',
                      'foo()[1]',
                      '$foo + $key + $value',
                     );

?>
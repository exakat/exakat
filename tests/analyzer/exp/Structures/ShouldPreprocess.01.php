<?php

$expected     = array('\'a\' . "b"',
                      'true * "b"',
                      '2 << 3',
                      'null + 2.2', 
                      '4 - 4', 
                      '2 + 4 - 4', 
                      '!(2 + 4 - 4)',
                      '6 and 7',
                      '8 ^ 9',
                      '\'a\' . Stdclass::c',
                      '8 ** CONSTANTE',
                      '\'a\' . strtolower("b")',
                      'strtolower("b")'
                      );

$expected_not = array('\'a\' . $a',
                      '__DIR__ + 1',
                      '\'a\' . $a->b',
                      '\'a\' . rand("b")',
                      '\'a\' . Stdclass::$D',
                      '\'a\' % Stdclass::D()',
                      '\'a\' >> $c->d()');

?>
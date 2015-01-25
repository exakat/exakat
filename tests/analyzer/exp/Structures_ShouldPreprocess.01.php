<?php

$expected     = array('\'a\' . "b"',
                      'true * "b"',
                      '2 << 3',
                      'null + 2.2', 
                      '2 + 4', 
                      '2 + 4 - 4', 
                      '!( 2 + 4 - 4)',
                      '6 and 7',
                      '8 ^ 9');

$expected_not = array('\'a\' . $a',
                      '__DIR__ + 1',
                      '\'a\' . $a->b',
                      '\'a\' . Stdclass::c',
                      '\'a\' . Stdclass::$D',
                      '\'a\' % Stdclass::D()',
                      '\'a\' >> $c->d()');

?>
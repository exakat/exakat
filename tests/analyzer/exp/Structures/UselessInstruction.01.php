<?php

$expected     = array('true',
                      'Stdclass::x',
                      'Stdclass::$x',
                      '__DIR__',
                      '$x[2 + 3]',
                      '1 + 1',
                      '2 * 3',
                      '$x->p',
                     );

$expected_not = array('Stdclass::x2',
                      'f(__FILE__, false)',
                      'f(3 * 4)',
                     );

?>
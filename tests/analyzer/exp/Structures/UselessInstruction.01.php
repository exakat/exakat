<?php

$expected     = array('true',
                      'Stdclass::x',
                      'Stdclass::$x',
                      '__DIR__',
                      '$x[2 + 3]',
                      '1 + 1',
                      '2 * 3',
                      '$x->p',
                      'f(__FILE__, false)',
                      'f(3 * 4)',
                     );

$expected_not = array('Stdclass::x2',
                     );

?>
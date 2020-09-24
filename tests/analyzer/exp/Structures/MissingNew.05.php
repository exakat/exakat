<?php

$expected     = array('xc( )',
                      'xi( )',
                     );

$expected_not = array('',
                      'xi( )',
                      'const xc = 1',
                      'const xi = 1',
                      'const x  = 1',
                      'const XC = 2',
                      'const XI = 3',
                      'const C = 3',
                      'const xC = 12',
                      'const xI = 13',
                      'const I = 13',
                     );

?>
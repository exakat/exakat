<?php

$expected     = array('use some\\namespace2 { /**/ } ',
                      'use some\\namespace2 { /**/ } ',
                      'use const some\\namespace2 { /**/ } ',
                      'use function some\\namespace2 { /**/ } ',
                      'use C1 as D1, C2, C3 as D3, C4\\C5\\C5 as D4, C6\\C6',
                      'use C as D',
                      'use some\\namespace2 { /**/ } ',
                      'use some\\namespace2 { /**/ } ',
                      'use const some\\namespace2 { /**/ } ',
                      'use function some\\namespace2 { /**/ } ',
                      'use c1 as d1, c2, c3 as d3, c4\\c5\\c5 as d4, c6\\c6',
                      'use c as d',
                     );

$expected_not = array('use A',
                      'use traitT',
                      'use a',
                      'use traitt',
                     );

?>
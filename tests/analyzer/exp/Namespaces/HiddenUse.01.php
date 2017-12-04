<?php

$expected     = array('use some\\namespace2 { /**/ } ',
                      'use some\\namespace2 { /**/ } ',
                      'use const some\\namespace2 { /**/ } ',
                      'use function some\\namespace2 { /**/ } ',
                      'use C1 as D1, C2, C3 as D3, C4\\C5\\C5 as D4, C6\\C6',
                      'use C as D',
                     );

$expected_not = array('use A',
                      'use traitT',
                     );

?>
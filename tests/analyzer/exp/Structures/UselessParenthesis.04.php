<?php

$expected     = array('(getArray( ))',
                      'array_pop((getArray( )))',
                      '[3, 4] + [5 + 6]',
                     );

$expected_not = array('([3, 4] + [5 + 6])',
                      '(1 + 3)',
                      'getArray( )',
                     );

?>
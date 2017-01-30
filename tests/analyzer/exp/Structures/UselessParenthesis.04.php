<?php

$expected     = array('(getArray( ))',
                      'getArray( )',
                      '[1, 2, 3] + ([3, 4] + [5 + 6])');

$expected_not = array('[3, 4] + [5 + 6]',
                      '(1 + 3)',
                      '([3, 4] + [5 + 6])');

?>
<?php

$expected     = array('new \\X',
                      'new \\X(1)',
                      'new \\X( )',
                     );

$expected_not = array('new \\x()',
                      'new \\x(1)',
                      'new \\x( )',
                     );

?>
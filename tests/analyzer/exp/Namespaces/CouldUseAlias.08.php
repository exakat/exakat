<?php

$expected     = array('A\\C',
                      '\\A\\C',
                      'A\\foo( )',
                      '\\A\\foo( )',
                      'a\\FOO( )',
                     );

$expected_not = array('B\\A\\foo( )',
                      '\\A\\C(2)',
                     );

?>
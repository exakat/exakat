<?php

$expected     = array('\\a\\b\\d\\e( )',
                      '\\a\\b\\c( )',
                      '\\A\\B\\DD(new \\A\\B\\D( ), $o->m( ) . \'/d\')',
                      '\\A\\B\\D( )',
                     );

$expected_not = array('\\a\\b\\w',
                     );

?>
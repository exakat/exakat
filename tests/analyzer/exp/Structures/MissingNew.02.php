<?php

$expected     = array('X( )',
                      'X',
                      'Stdclass( )',
                      'Stdclass',
                      '\\Stdclass( )',
                      '\\Stdclass',
                     );

$expected_not = array('chdir(\'.\')',
                      'Z( )',
                      '\\Z( )',
                      'Z',
                      '\\Z',
                      'Y( )',
                      '\\Y( )',
                     );

?>
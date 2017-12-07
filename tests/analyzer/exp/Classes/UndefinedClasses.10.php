<?php

$expected     = array('new b\\e',
                      'new b\\e( )',
                     );

$expected_not = array('$d instanceof b',
                      'new b( )',
                      'new b\\d',
                      'new b\\d( )',
                      '$c instanceof b\\d',
                      'b\\d',
                      'b',
                     );

?>
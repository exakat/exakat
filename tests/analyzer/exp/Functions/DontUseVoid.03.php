<?php

$expected     = array('array(\'X\', \'foo\')( )',
                      '"X::foo"( )',
                     );

$expected_not = array('array(\'X\', \'foo\')(1)',
                      '"X::foo"(1)',
                      'new x( )',
                     );

?>
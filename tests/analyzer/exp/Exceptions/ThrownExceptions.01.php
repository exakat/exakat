<?php

$expected     = array('$x = new Exception( )',
                      'new Exception2( )',
                      'new Exception( )',
                      '$x',
                     );

$expected_not = array('Exception3( )',
                      '$b',
                      'throw($b)',
                     );

?>
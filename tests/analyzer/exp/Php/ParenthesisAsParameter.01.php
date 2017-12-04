<?php

$expected     = array('squareArray((getArray( )), (f( )), (f2( )))',
                      'squareArray((getArray( )), (f( )))',
                      'squareArray((getArray( )))',
                     );

$expected_not = array('(1) + (strtolower($x))',
                      '(strtoupper($x))',
                     );

?>
<?php

$expected     = array( 'empty($a->foo($a))', 
                       'empty(A::foo($a))',
                       'empty(foo($a))',
                       'empty($foo($a))',
                       'empty(array( ))', 
                       'empty([ \'\' ])');

$expected_not = array('empty(${a})');

?>
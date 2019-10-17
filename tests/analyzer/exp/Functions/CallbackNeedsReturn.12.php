<?php

$expected     = array('array_map(\'foo\', array( ))',
                      'array_map($a, array( ))',
                     );

$expected_not = array('array_map($a2, array( ))',
                      'array_map(\'foo2\', array( ))',
                     );

?>
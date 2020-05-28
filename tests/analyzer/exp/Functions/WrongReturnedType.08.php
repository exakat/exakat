<?php

$expected     = array('return 3', 
                      'return $a->array_merge(array( ), array( ))',
                     );

$expected_not = array('return array_merge(array( ), array( ))',
                     );

?>
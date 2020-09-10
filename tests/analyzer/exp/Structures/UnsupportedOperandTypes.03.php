<?php

$expected     = array('1 + $o->p', 
                      '1 + b::$sp',
                     );

$expected_not = array('array( ) + $o->p', 
                      'array( ) + b::$sp',
                     );

?>
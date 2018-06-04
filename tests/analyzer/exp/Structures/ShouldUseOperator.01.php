<?php

$expected     = array('is_null($var)', 
                      'array_push($a, \'b\')', 
                      'chr(69)',
                      'php_version( )',
                     );

$expected_not = array('array_pop($a)',
                     );

?>
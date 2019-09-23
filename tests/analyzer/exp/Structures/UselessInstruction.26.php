<?php

$expected     = array('strtolower(\'a\')', 
                      'bar($b)',
                     );

$expected_not = array('sort($a)',
                      'array_shift($a)',
                      'foo($a)',
                     );

?>
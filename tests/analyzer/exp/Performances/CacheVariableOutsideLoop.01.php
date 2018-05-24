<?php

$expected     = array('strtolower($x->x)', 
                      'strtolower($x->x + $a)', 
                      'dirname(__DIR__)', 
                      'dirname(__DIR__ . $a)',
                     );

$expected_not = array('strtolower($x->x + $d)', 
                      'strtolower($x->x + $e)', 
                      'dirname(__DIR__ . $b)',
                     );

?>
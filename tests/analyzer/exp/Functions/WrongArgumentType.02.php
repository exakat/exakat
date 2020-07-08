<?php

$expected     = array('foo(\'a\')', 
                      'foo(B)',
                      'foo(3 . \'3\')',
                      'foo((3 . \'3\'))',
                      'foo(@(3 . \'3\'))',
                      'foo($c = 3 . \'3\')',
                     );

$expected_not = array('foo(\'a\')', 
                      'foo(B)',
                      'foo($d = 3)',
                      'foo($d = 3 + 5)',
                     );

?>
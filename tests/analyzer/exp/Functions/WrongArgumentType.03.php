<?php

$expected     = array( 'foo(D)', 
                       'foo(@(3 === \'3\'))',
                     );

$expected_not = array('foo(B)',
                      'foo(3 . \'3\')',
                      'foo((3 . \'3\'))',
                     );

?>
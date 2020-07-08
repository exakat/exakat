<?php

$expected     = array('foo(3)',
                      'foo($d = 3)',
                      'foo(D)',
                     );

$expected_not = array('foo(\'a\')',
                      'foo(B)',
                      'foo(DE)',
                     );

?>
<?php

$expected     = array('(new x)->foo(1, 2)',
                      '(new x( ))::bar(1, 2)',
                     );

$expected_not = array('(new x)->foo($a, $b)',
                      '(new x( ))::bar($a, $b)',
                     );

?>
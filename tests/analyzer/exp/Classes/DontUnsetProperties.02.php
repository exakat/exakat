<?php

$expected     = array('(unset) A::$b',
                      'unset(A::$a)',
                     );

$expected_not = array('(unset) A::$b[1]',
                      'unset(A::$a[1])',
                     );

?>
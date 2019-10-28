<?php

$expected     = array('foo2(array(1))',
                      'foo2(\\D)',
                      'foo2(C)',
                      'foo2($a + $b)',
                      'foo2(1)',
                     );

$expected_not = array('foo2("1")',
                      'foo2($a . $b)',
                     );

?>
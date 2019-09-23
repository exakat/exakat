<?php

$expected     = array('($a1 = new x)->p',
                      '$a->b($a4 = new x)',
                      '($a2 = new x)->m',
                      '($a3 = new x)::$p',
                     );

$expected_not = array('($a5 = new x)',
                     );

?>
<?php

$expected     = array('\\A\\B',
                      'strtolower($e)',
                      'A',
                      'A::F',
                     );

$expected_not = array('$a',
                      '$b->c',
                      '$d[\'e\']',
                      'A::$f',
                     );

?>